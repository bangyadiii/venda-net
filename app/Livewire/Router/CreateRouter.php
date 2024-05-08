<?php

namespace App\Livewire\Router;

use App\Livewire\Forms\RouterForm;
use App\Models\Router;
use Livewire\Component;

class CreateRouter extends Component
{
    public RouterForm $form;

    public function render()
    {
        return view('livewire.router.create-router');
    }

    public function store()
    {
        $this->validate();
        Router::create($this->form->all());

        $this->dispatch('toast', title: 'Saved to database', type: 'success');
        return redirect()->route('routers.index');
    }

    public function testConnection(): void
    {
        $this->validate(
            [
                'form.host' => 'required|string',
                'form.username' => 'required',
                'form.password' => 'nullable',
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
