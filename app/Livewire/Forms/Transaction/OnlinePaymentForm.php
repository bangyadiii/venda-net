<?php

namespace App\Livewire\Forms\Transaction;

use App\Enums\BillStatus;
use App\Models\Bill;
use App\Models\Customer;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OnlinePaymentForm extends Form
{
    public $id;
    public $customer_name;
    public $phone_number;
    public $address;
    public $plan_name;
    public $plan_price;
    public $tax;
    public $total;
    public $billStatus = 'LUNAS';

    public function setCustomer(Customer $customer)
    {
        $this->id = $customer->id;
        $this->customer_name = $customer->customer_name;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
        $this->plan_name = $customer->plan->name;
        $this->plan_price = $customer->plan->price;
    }

    public function setBill(?Bill $bill)
    {
        $this->tax = $bill->tax_rate;
        $this->total = $bill->total_amount;
        $this->billStatus = isset($bill) && $bill->status == BillStatus::PAID
            ? 'LUNAS' : 'BELUM DIBAYAR';
    }
}
