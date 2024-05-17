<?php

namespace App\Livewire\Router;

use App\Livewire\Forms\RouterForm;
use App\Models\Router;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use RouterOS\Query;

class EditRouter extends Component
{
    public ?Router $router;
    public ?RouterForm $form;

    public function mount($id)
    {
        $this->router = Router::findOrFail($id);
        $this->form->setRouter($this->router);
        try {
            $client = Router::getClient($this->form->host, $this->form->username, $this->form->password);
            $query = new Query('/ppp/profile/print');
            $this->form->profiles = $client->query($query)->read();
        } catch (\Throwable $th) {
            $this->form->profiles = [];
        }
    }

    public function render()
    {
        return view('livewire.router.edit-router');
    }

    public function store()
    {
        $this->form->validate();
        $this->router->fill($this->form->only(
            Router::make()->getFillable()
        ));
        $this->router->saveOrFail();

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
            $client = Router::getClient($this->form->host, $this->form->username, $this->form->password);
            $this->form->is_connected = true;
            $query = new Query('/ppp/profile/print');
            $this->form->profiles = $client->query($query)->read();
            $this->dispatch('toast', title: 'Connection successful', type: 'success');
        } catch (\Throwable $th) {
            $this->form->is_connected = false;
            $this->form->profiles = [];
            $this->dispatch('toast', title: 'Connection failed', type: 'danger');
        }
    }
}
