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
        $this->form->validate();
        Router::create($this->form->all());

        $this->dispatch('toast', title: 'Saved to database', type: 'success');
        return redirect()->route('routers.index');
    }

    public function testConnection()
    {
        $this->validate(
            [
                'form.host' => 'required|string',
                'form.username' => 'required',
                'form.password' => 'nullable',
            ]
        );
        try {
            Router::getClient($this->form->host, $this->form->username, $this->form->password);
            $this->dispatch('toast', title: 'Connection successful', type: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: 'Connection failed', type: 'danger');
            dd($th->getMessage());
        }
    }
}
