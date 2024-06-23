<?php

namespace App\Livewire\Router;

use App\Livewire\Forms\RouterForm;
use App\Models\Client;
use App\Models\Profile;
use App\Models\Router;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use RouterOS\Exceptions\ConnectException;

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

            $this->dispatch('toast', title: 'Data berhasil disimpan', type: 'success');
            return $this->redirectRoute('routers.index', navigate: true);
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: 'Oops... Data gagal untuk disimpan', type: 'error');
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
        $client = new Client();
        if (!$client->connect($this->form->host, $this->form->username, $this->form->password)) {
            $this->form->is_connected = false;
            $this->form->profiles = [];
            $this->dispatch('toast', title: 'Tidak bisa terkoneksi', type: 'error');
            return;
        }
        $this->form->is_connected = true;
        $profiles = $client->comm('/ppp/profile/print');
        $this->form->profiles = \array_map(fn ($profile) => [
            'id' => $profile['.id'],
            'name' => $profile['name'],
        ], $profiles);

        $this->dispatch('toast', title: 'Koneksi berhasil', type: 'success');
        $client->disconnect();
    }
}
