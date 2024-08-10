<?php

namespace App\Livewire\Setting;

use App\Enums\BillStatus;
use App\Livewire\Forms\SettingForm;
use App\Models\Bill;
use App\Models\Setting;
use Livewire\Component;

class SettingComponent extends Component
{
    public SettingForm $form;
    public $data = [];

    public function mount()
    {
        $this->data['enabled'] = (bool) Setting::query()->where('key', 'reminder_enabled')->first()?->value ?? 0;
        $this->data['template'] = Setting::query()->where('key', 'whatsapp_template')->first()?->value ?? '';
        $this->data['ppn'] = (int) Setting::query()->where('key', 'ppn')->first()?->value ?? 0;
        $this->data['rekening'] = Setting::query()->where('key', 'rekening')->first()?->value ?? '';
        $this->form->setData($this->data);
    }

    public function render()
    {
        return view('livewire.setting.setting-component');
    }

    public function cancel()
    {
        $this->form->reset();
        $this->form->setData($this->data);
    }

    public function store()
    {
        $this->form->validate();

        $enabled = Setting::query()->updateOrCreate([
            'key' => 'reminder_enabled'
        ], [
            'value' => $this->form->enabled
        ]);
        $template = Setting::query()->updateOrCreate([
            'key' => 'whatsapp_template'
        ], [
            'value' => $this->form->template
        ]);
        $ppn = Setting::query()->updateOrCreate([
            'key' => 'ppn'
        ], [
            'value' => $this->form->ppn
        ]);

        $bills = Bill::query()
            ->with('plan')
            ->where('status', BillStatus::UNPAID)
            ->whereDate('due_date', '>', now())
            ->get();

        foreach ($bills as $bill) {
            $bill->update([
                'tax_rate' => $this->form->ppn,
                'total_amount' => $bill->plan->price * ($this->form->ppn / 100 + 1)
            ]);
        }

        $rekening = Setting::query()->updateOrCreate([
            'key' => 'rekening'
        ], [
            'value' => $this->form->rekening
        ]);

        $this->dispatch('toast', title: 'Setting berhasil tersimpan');
        $this->form->reset();

        $this->form->setData([
            'enabled' => (bool) $enabled->value,
            'template' => $template->value,
            'ppn' => $ppn->value,
            'rekening' => $rekening->value
        ]);
    }
}
