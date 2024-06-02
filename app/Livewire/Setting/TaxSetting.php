<?php

namespace App\Livewire\Setting;

use App\Models\Setting;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TaxSetting extends Component
{
    private ?Setting $setting;

    #[Validate('required|integer|min:0|max:100')]
    public int $ppn;

    public function __construct()
    {
        $this->setting = Setting::query()->firstOrCreate([
            'key' => 'ppn'
        ], ['value' => 11]);
    }

    public function mount()
    {
        $this->ppn = $this->setting?->value ?? 0;
    }

    public function render()
    {
        return view('livewire.setting.tax-setting');
    }

    public function store()
    {
        $this->setting->value = $this->ppn;
        $this->setting->save();

        $this->dispatch('toast', title: 'Data berhasil disimpan');
        return $this->redirect(route('tax'), navigate: true);
    }
}
