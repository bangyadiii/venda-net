<?php

namespace App\Livewire\Router;

use App\Models\Router;
use Illuminate\Support\Collection;
use Livewire\Component;

class RouterIndex extends Component
{
    public ?Collection $routers;

    public function mount()
    {
        $this->routers =  Router::all();
        $this->routers->each(function($router) {
            $router->isConnected = $router->isConnected();
        });
    }

    public function render()
    {
        return view('livewire.router.router-index', ['routers' => $this->routers]);
    }

    public function delete($id)
    {
        Router::destroy($id);
        $this->dispatch('toast', title: 'Deleted from database', type: 'success');
    }
}
