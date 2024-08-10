<?php

namespace App\Livewire\Forms;

use App\Enums\PaymentMethod;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public $discount;
    public $method;
    public $note;
    #[Locked]
    public $tax_rate;

    public function rules()
    {
        return [
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'note' => 'nullable|string|max:255',
            'discount' => 'nullable|integer',
        ];
    }

    public array $validationAttributes = [
        'method' => 'Metode',
        'note' => 'Catatan',
        'discount' => 'Diskon',
    ];
}
