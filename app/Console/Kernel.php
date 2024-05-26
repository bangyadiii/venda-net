<?php

namespace App\Console;

use App\Jobs\GenerateMonthlyBills;
use App\Models\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('telescope:prune --hours=48')->daily();
        $schedule->job(new GenerateMonthlyBills())
            ->monthlyOn(1, '00:00');
        $schedule->command('check:late-payments')->dailyAt('00:30');
        $schedule->command('app:payment-reminder-command')
            ->dailyAt('09:00')
            ->when(function () {
                $enabled = Setting::where('key', 'reminder_enabled')->first();
                if (!$enabled || !$enabled->value) {
                    return false;
                }
                return (bool) $enabled->value;
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
