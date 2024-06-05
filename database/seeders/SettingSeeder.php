<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ppn
        Setting::create([
            'key' => 'ppn',
            'value' => 11
        ]);

        // reminder
        Setting::create([
            'key' => 'reminder_enabled',
            'value' => true,
        ]);

        Setting::create([
            'key' => 'whatsapp_template',
            'value' => config('template-notif.template'),
        ]);

        Setting::create([
            'key' => 'rekening',
            'value' => 'BCA 1234567890 a/n PT. ABCD',
        ]);
    }
}
