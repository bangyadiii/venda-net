<?php

namespace App\Livewire\Transaction;

use App\Livewire\Forms\Transaction\OnlinePaymentForm;
use App\Models\Bill;
use App\Models\Customer;
use Livewire\Component;
use Sawirricardo\Midtrans\Dto\TransactionDto;
use Sawirricardo\Midtrans\Laravel\Facades\Midtrans;

class CreateOnlinePayment extends Component
{
    public ?Customer $customer;
    public ?Bill $bill;
    public OnlinePaymentForm $form;

    public function mount($id)
    {
        $this->customer = Customer::with('plan')->findOrFail($id);
        $this->bill = Bill::query()->where('customer_id', $id)
            ->where('status', 'unpaid')->first();

        $this->form->setCustomer($this->customer);
        $this->form->setBill($this->bill);
    }

    public function render()
    {
        return view('livewire.transaction.create-online-payment')
            ->layout('layouts.blankLayout');
    }

    public function store()
    {
        $orderId = \rand(1, 5000) . '.' . $this->bill->id;
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $this->bill->total_amount,
            ],
            'customer_details' => [
                'first_name' => $this->customer->customer_name,
                'phone' => $this->customer->phone_number,
            ],
        ];

        try {
            $transactionToken = Midtrans::snap()
                ->create(new TransactionDto($params));

            $this->dispatch('midtrans:payment', snapToken: $transactionToken->token);
        } catch (\Exception $e) {
            $this->dispatch('toast', title: $e->getMessage(), type: 'error');
        }
    }
}
