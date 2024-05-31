<?php

namespace App\Livewire\Transaction;

use App\Livewire\Forms\BillCheckForm;
use Livewire\Component;

class BillCheck extends Component
{
    public BillCheckForm $form;

    public function render()
    {
        return view('livewire.transaction.bill-check')
            ->layout('layouts.blankLayout');
    }

    public function store()
    {
        $this->form->validate();
        return $this->redirectRoute('payment.index', ['id' => $this->form->customer_id], navigate: true);
    }
}
