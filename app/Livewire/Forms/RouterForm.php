<?php

namespace App\Livewire\Forms;

use App\Models\Router;
use Livewire\Form;

class RouterForm extends Form
{
    public ?Router $router;

    public string $host;
    public $username;
    public $password;
    public $auto_isolir;
    public $isolir_action;
    public $isolir_profile_id;

    public array $rules = [
        'host' => 'required|string',
        'username' => 'required|string',
        'password' => 'nullable|string',
        'auto_isolir' => 'required|boolean',
        'isolir_action' => 'required_if:auto_isolir,true|in:change_profile,disable_secret',
        'isolir_profile_id' => 'required_if:isolir_action,change_profile|integer',
    ];

    public function setRouter(Router $router)
    {
        $this->host = $router->host;
        $this->username = $router->username;
        $this->password = $router->password;
        $this->auto_isolir = $router->auto_isolir;
        $this->isolir_action = $router->isolir_action;
        $this->isolir_profile_id = $router->isolir_profile_id;
    }
}
