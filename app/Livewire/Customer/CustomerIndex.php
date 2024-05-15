<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Livewire\Component;

class CustomerIndex extends Component
{
    public function render()
    {
        return view('livewire.customer.customer-index');
    }
}
