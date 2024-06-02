<?php

namespace App\Livewire\Router;

use App\Livewire\Forms\RouterForm;
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
        try {
            $client = Router::getClient($this->form->host, $this->form->username, $this->form->password);
            $this->form->is_connected = true;
            $this->form->profiles = Profile::queryForClient($client)->get()->toArray();

            $this->dispatch('toast', title: 'Koneksi berhasil', type: 'success');
        } catch (ConnectException $th) {
            Log::error($th->getMessage());
            $this->form->is_connected = false;
            $this->form->profiles = [];
            $this->dispatch('toast', title: 'Tidak bisa terkoneksi', type: 'error');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
    }
}
