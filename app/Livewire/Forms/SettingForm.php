<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SettingForm extends Form
{
    public bool $enabled = true;
    public string $template = '';
    public $ppn = 0;
    public ?string $rekening = '';
    public ?bool $changed = false;

    public array $rules = [
        'enabled' => 'required|boolean',
        'template' => 'required|string|min:2|max:1200',
        'ppn' => 'required|integer|min:0|max:100',
        'rekening' => 'nullable|string|min:2|max:255'
    ];

     public array $validationAttributes = [
        'enabled' => 'Enabled',
        'template' => 'Template',
        'ppn' => 'PPN',
        'rekening' => 'Rekening'
    ];

    public function setData(array $data)
    {
        $this->enabled = $data['enabled'];
        $this->template = $data['template'];
        $this->ppn = $data['ppn'];
        $this->rekening = $data['rekening'];
    }
}
