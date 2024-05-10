<?php

namespace App\Livewire\Forms;

use App\Models\Plan;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PlanForm extends Form
{
    public $name;
    public $speed_limit;
    public $price;
    public $router_id;

    public array $rules = [
        'name' => 'required|string',
        'speed_limit' => 'required|integer',
        'price' => 'required|numeric',
        'router_id' => 'required|integer',
    ];

    public function setPlan(Plan $plan){
        $this->name = $plan->name;
        $this->speed_limit = $plan->speed_limit;
        $this->price = $plan->price;
        $this->router_id = $plan->router_id;
    }
}
