<?php

namespace App\Livewire\Router;

use App\Livewire\Forms\RouterForm;
use App\Models\Router;
use Livewire\Component;

class EditRouter extends Component
{
    public ?RouterForm $form;

    public function mount($id)
    {
        $router = Router::findOrFail($id);
        $this->form->setRouter($router);
    }

    public function render()
    {
        return view('livewire.router.edit-router');
    }

    public function store()
    {
        $this->validate();
        Router::create($this->form->all());

        $this->dispatch('toast', title: 'Saved to database', type: 'success');
        return redirect()->route('routers.index');
    }

    public function testConnection()
    {
        $this->validate(
            [
                'router.host' => 'required|numeric',
                'router.username' => 'required',
                'router.password' => 'nullable',
            ]
        );
        try {
            Router::getClient($this->host, $this->username, $this->password);
            $this->dispatch('toast', title: 'Connection successful', type: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: 'Connection failed', type: 'danger');
        }
    }
}
