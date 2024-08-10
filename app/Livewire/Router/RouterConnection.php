<?php

namespace App\Livewire\Router;

use App\Models\Router;
use Livewire\Component;

class RouterConnection extends Component
{
    public $isConnected = false;

    public function mount(Router $router)
    {
        $this->isConnected = $router->isConnected();
    }

    public function render()
    {
        return view('livewire.router.router-connection');
    }

    // skeleton UI
    public function placeholder()
    {
        return <<<'HTML'
        <div class='placeholder-glow' style="width: 100px">
        <span class="placeholder bg-secondary w-100"></span>
        </div>
        HTML;
    }
}
