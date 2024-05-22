<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public $discount;
    public $method;
    public $note;
    public $tax_rate;

    public array $rules = [
        'method' => 'required|string|max:255',
        'note' => 'nullable|string|max:255',
        'discount' => 'nullable|integer',
    ];
}
