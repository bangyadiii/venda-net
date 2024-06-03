<?php

namespace App\Livewire\Setting;

use App\Livewire\Forms\NotificationForm;
use App\Models\Setting;
use Livewire\Component;

class NotificationSetting extends Component
{
    public NotificationForm $form;
    public $enabled;
    public $template;

    public function mount()
    {
        $this->enabled = Setting::query()->where('key', 'reminder_enabled')->first() ?? 0;
        $this->template = Setting::query()->where('key', 'whatsapp_template')->first()->value ?? '';

        $this->form->setData([
            'enabled' => (bool) $this->enabled,
            'template' => $this->template
        ]);
    }

    public function render()
    {
        return view('livewire.setting.notification-setting');
    }

    public function cancel()
    {
        $this->form->reset();
        $this->form->setData([
            'enabled' => (bool) $this->enabled,
            'template' => $this->template
        ]);
    }

    public function store()
    {
        $this->form->validate();

        Setting::query()->updateOrCreate([
            'key' => 'reminder_enabled'
        ], [
            'value' => $this->form->enabled
        ]);
        Setting::query()->updateOrCreate([
            'key' => 'whatsapp_template'
        ], [
            'value' => $this->form->template
        ]);

        $this->dispatch('toast', title: 'Setting berhasil tersimpan');
        $this->form->reset();
        $this->form->setData([
            'enabled' => (bool) $this->enabled,
            'template' => $this->template
        ]);
        return \redirect()->back();
    }
}
