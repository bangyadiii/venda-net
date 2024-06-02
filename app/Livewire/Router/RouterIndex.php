<?php

namespace App\Livewire\Router;

use App\Models\Profile;
use App\Models\Router;
use Illuminate\Support\Collection;
use Livewire\Component;

class RouterIndex extends Component
{
    public ?Collection $routers;

    public function render()
    {
        $this->routers = Router::all();

        return view('livewire.router.router-index', ['routers' => $this->routers]);
    }

    public function delete($id)
    {
        $router = Router::with('plans.customers')->find($id);
        if ($router->has('plans.customers')) {
            return $this->dispatch('toast', title: 'Tidak bisa menghapus router karena digunakan oleh pelanggan', type: 'success');
        }
        $router->delete();
        return $this->dispatch('toast', title: 'Data berhasil di hapus', type: 'success');
    }
}
