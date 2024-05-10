<?php

namespace App\Livewire\Forms;

use App\Models\Plan;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PlanForm extends Form
{
    public $name;
    public $speed_limit;
    public $download_speed;
    public $upload_speed;
    public $isSameSpeed = false;
    public $price;
    public $router_id;

    public array $rules = [
        'name' => 'required|string',
        'download_speed' => 'required|numeric',
        'upload_speed' => 'nullable|numeric',
        'isSameSpeed' => 'required|boolean',
        'price' => 'required|numeric',
        'router_id' => 'required|integer',
    ];

    public function setPlan(Plan $plan)
    {
        $this->name = $plan->name;
        $this->download_speed = (int)str_replace('M', '', \explode('/', $plan->speed_limit)[0]) ?? 0;
        $this->upload_speed = (int)str_replace('M', '', \explode('/', $plan->speed_limit)[1]) ?? 0;
        $this->price = $plan->price;
        $this->router_id = $plan->router_id;
        $this->isSameSpeed = $this->download_speed === $this->upload_speed;
    }

    public function validate($rules = null, $messages = [], $attributes = [])
    {
        if ($this->isSameSpeed) {
            $this->upload_speed = $this->download_speed;
        }
        $this->speed_limit = $this->download_speed . 'M/' . $this->upload_speed . 'M';
        return parent::validate($rules ?? $this->rules, $messages, $attributes);
    }
}
