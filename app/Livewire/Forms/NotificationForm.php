<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class NotificationForm extends Form
{
    public bool $enabled = true;
    public string $template = '';

    public function setData(array $data)
    {
        $this->enabled = $data['enabled'];
        $this->template = $data['template'];
    }

    public array $rules = [
        'enabled' => 'required|boolean',
        'template' => 'required|string|min:2|max:1200',
    ];

    public array $validationAttributes = [
        'enabled' => 'Enabled',
        'template' => 'Template',
    ];
}
