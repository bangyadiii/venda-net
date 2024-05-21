<?php

namespace App\Livewire;

use App\Classes\Invoice;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Plan;
use Carbon\Carbon;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Livewire\Component;

class ViewInvoice extends Component
{
    private Invoice $invoice;
    private Bill $bill;
    private Customer $customer;
    private Plan $plan;

    public function mount($id)
    {
        $this->bill = Bill::query()->with(['customer', 'plan'])->findOrFail($id);
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

        return Invoice::make()
            ->buyer($buyer)
            ->template('template')
            ->taxRate($this->bill->tax_rate)
            ->addItem($item)
            ->dueDate(Carbon::parse($this->bill->due_date))
            ->status($this->bill->status == 'paid' ? __('invoices::invoice.paid') : __('invoices::invoice.unpaid'))
            ->filename($this->customer->id . '_' . $this->bill->id)
            ->save('public');
    }
}
