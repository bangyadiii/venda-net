<?php

namespace App\Livewire\Router;

use App\Livewire\Forms\RouterForm;
use App\Models\Router;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use RouterOS\Query;

class CreateRouter extends Component
{
    public RouterForm $form;

    public function render()
    {
        return view('livewire.router.create-router');
    }

    public function store()
    {
        $this->form->validate();
        try {
            Router::create($this->form->all());

            $this->dispatch('toast', title: 'Saved to database', type: 'success');
            return redirect()->route('routers.index');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: 'Failed to save to database', type: 'danger');
        }
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
            $client = Router::getClient($this->form->host, $this->form->username, $this->form->password);
            $this->form->is_connected = true;
            $query = new Query('/ppp/profile/print');
            $this->form->profiles = $client->query($query)->read();
            $this->dispatch('toast', title: 'Connection successful', type: 'success');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->form->is_connected = false;
            $this->form->profiles = [];
            $this->dispatch('toast', title: 'Connection failed', type: 'danger');
        }
    }
}
