<?php

namespace App\Livewire;

use App\Livewire\Forms\NotificationForm;
use App\Models\Setting;
use Livewire\Component;

class NotificationSetting extends Component
{
    public NotificationForm $form;

    public function mount()
    {
        $enabled = Setting::query()->where('key', 'reminder_enabled')->first() ?? 0;
        $template = Setting::query()->where('key', 'whatsapp_template')->first()->value ?? '';

        $this->form->setData([
            'enabled' => (bool) $enabled,
            'template' => $template
        ]);
    }

    public function render()
    {
        return view('livewire.notification-setting');
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

        $this->dispatch('toast', title: 'Notification setting has been saved');
        return \redirect()->back();
    }
}
