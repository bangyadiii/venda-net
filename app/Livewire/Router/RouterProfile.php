<?php

namespace App\Livewire\Router;

use App\Models\Profile;
use App\Models\Router;
use Livewire\Component;

class RouterProfile extends Component
{
    public Router $router;

    public function mount(Router $router)
    {
        $this->router = $router;
        try {
            $client = Router::getClient($router->host, $router->username, $router->password);
            $profiles = Profile::queryForClient($client)->get();
            $this->router->profiles = $profiles;
        } catch (\Throwable $th) {
            //
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class='placeholder-glow' style="width: 100px">
        <span class="placeholder bg-secondary w-100"></span>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.router.router-profile');
    }
}
