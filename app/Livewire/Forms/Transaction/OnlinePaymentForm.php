<?php

namespace App\Livewire\Forms\Transaction;

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
    public $billStatus = 'paid';

    public function setCustomer(Customer $customer)
    {
        $this->id = $customer->id;
        $this->customer_name = $customer->customer_name;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
        $this->plan_name = $customer->plan->name;
        $this->plan_price = $customer->plan->price;
    }

    public function setBill($bill)
    {
        $this->billStatus = isset($bill) ? 'unpaid' : 'paid';
    }
}
