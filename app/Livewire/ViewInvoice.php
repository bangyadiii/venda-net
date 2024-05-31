<?php

namespace App\Livewire;

use App\Classes\Invoice;
use App\Enums\BillStatus;
use App\Enums\PaymentStatus;
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
    public ?int $billId;

    #[Url(as: 'order_id', except: '')]
    public ?string $orderId;

    #[Url(as: 'transaction_status', except: '')]
    public ?string $trxStatus;

    public function mount()
    {
        if (!isset($this->billId) && !isset($this->orderId)) {
            throw new NotFoundHttpException();
        }

        if ($this->billId) {
            $this->bill = Bill::query()->with(['customer', 'plan'])->findOrFail($this->billId);
        } elseif ($this->orderId) {
            $billId = \explode('.', $this->orderId)[1];
            $this->bill = Bill::query()->where('id', $billId)->with(['customer', 'plan'])->first();

            if ($this->bill->status == BillStatus::UNPAID && $this->trxStatus == 'settlement') {
                $this->bill->status = BillStatus::PAID;
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
        if (isset($this->bill->payment) && $this->bill->payment->status == PaymentStatus::SUCCESS) {
            $invoice->paidDate(Carbon::parse($this->bill->payment->payment_date));
        }

        return $invoice->buyer($buyer)
            ->template('template')
            ->taxRate($this->bill->tax_rate)
            ->addItem($item)
            ->dueDate(Carbon::parse($this->bill->due_date))
            ->status($this->bill->status == BillStatus::PAID ? __('invoices::invoice.paid') : __('invoices::invoice.unpaid'))
            ->filename($this->customer->id . '_' . $this->bill->id)
            ->save('public');
    }
}
