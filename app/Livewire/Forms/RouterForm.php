<?php

namespace App\Livewire\Forms;

use App\Models\Router;
use Illuminate\Validation\Rule;
use Livewire\Form;

class RouterForm extends Form
{
    public ?Router $router = null;

    public string $host;
    public $username;
    public $password;
    public $isolir_action = 'disable_secret';
    public $isolir_profile_id;
    public $is_connected = false;

    public $profiles = [];

    public function rules()
    {
        return [
            'host' => [
                'required', 'string', Rule::unique('routers', 'host')->ignore($this->router?->id)
            ],
            'username' => 'required|string',
            'password' => 'nullable|string',
            'isolir_action' => 'required|in:change_profile,disable_secret',
            'isolir_profile_id' => 'nullable|required_if:isolir_action,change_profile|string',
        ];
    }

    public array $validationAttributes = [
        'host' => 'Host',
        'username' => 'Username',
        'password' => 'Password',
        'isolir_action' => 'Isolir Action',
        'isolir_profile_id' => 'Isolir Profile',
    ];

    public function setRouter(Router $router)
    {
        $this->host = $router->host;
        $this->username = $router->username;
        $this->password = $router->password;
        $this->isolir_action = $router->isolir_action;
        $this->isolir_profile_id = $router->isolir_profile_id;
    }
}
