<?php

namespace App\Livewire\Router;

use Livewire\Component;

class CreateRouter extends Component
{
    public function render()
    {
        return view('livewire.router.create-router')
            ->layout('layouts.commonMaster');
    }
}
