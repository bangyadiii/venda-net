<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class BillCheckForm extends Form
{
    public $customer_id;

    public array $rules = [
        'customer_id' => 'required|exists:customers,id',
    ];
}
