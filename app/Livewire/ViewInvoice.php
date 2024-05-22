<?php

namespace App\Livewire;

use App\Classes\Invoice;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Plan;
use Carbon\Carbon;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Livewire\Attributes\Url;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViewInvoice extends Component
{
    private Invoice $invoice;
    private Bill $bill;
    private Customer $customer;
    private Plan $plan;

    #[Url(as: 'bill_id', except: '')]
    public $billId;

    #[Url(as: 'order_id', except: '')]
    public $orderId;

    #[Url(as: 'transaction_status', except: '')]
    public $trxStatus;

    public function mount()
    {
        if (!$this->billId && !$this->orderId) {
            throw new NotFoundHttpException();
        }

        if ($this->billId) {
            $this->bill = Bill::query()->with(['customer', 'plan'])->findOrFail($this->billId);
        } elseif ($this->orderId) {
            $billId = \explode('.', $this->orderId)[1];
            $this->bill = Bill::query()->where('id', $billId)->with(['customer', 'plan'])->first();

            if ($this->bill->status == 'unpaid' && $this->trxStatus == 'settlement') {
                $this->bill->status = 'paid';
            }
        }

        $this->customer = $this->bill->customer;
        $this->plan = $this->bill->plan;
        $this->invoice = $this->createInvoice();
    }

    public function render()
    {
        if (auth()->check()) {
            return view('livewire.view-invoice', ['invoice' => $this->invoice]);
        }
        return view('livewire.view-invoice', ['invoice' => $this->invoice, 'customer_id' => $this->customer->id])
            ->layout('layouts.blankLayout');
    }

    private function createInvoice(): Invoice
    {
        $buyer = new Buyer([
            'name'          => $this->customer->customer_name,
            'phone' => $this->customer->phone_number,
            'address' => $this->customer->address,
        ]);

        $item = InvoiceItem::make($this->plan->name)
            ->pricePerUnit($this->plan->price)
            ->discount($this->bill->discount);

        $invoice = Invoice::make();
        if ($this->bill->payment->status == 'success') {
            $invoice->paidDate(Carbon::parse($this->bill->payment->payment_date));
        }

        return $invoice->buyer($buyer)
            ->template('template')
            ->taxRate($this->bill->tax_rate)
            ->addItem($item)
            ->dueDate(Carbon::parse($this->bill->due_date))
            ->status($this->bill->status == 'paid' ? __('invoices::invoice.paid') : __('invoices::invoice.unpaid'))
            ->filename($this->customer->id . '_' . $this->bill->id)
            ->save('public');
    }
}
