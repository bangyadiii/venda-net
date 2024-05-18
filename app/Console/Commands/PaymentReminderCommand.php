<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Setting;
use App\Notifications\PaymentReminderNotification;
use Illuminate\Console\Command;

class PaymentReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-reminder-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send payment reminder to customers.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $template = Setting::where('key', 'whatsapp_template')->first()->value;

        $customers = Customer::with(['bills', 'plan'])
            ->where('service_status', 'active')
            ->withWhereHas('bills', function ($query) {
                $query->whereDate('due_date', now()->addDay())
                    ->where('status', 'unpaid')
                    ->where('total_amount', '>', 0);
            })
            ->get();

        foreach ($customers as $customer) {
            $data = [
                'NOPEL' => $customer->id,
                'NAMA' => $customer->customer_name,
                'PHONE' => $customer->phone_number,
                'ALAMAT' => $customer->address,
                'PAKET' => $customer->plan->name,
                'TARIFPAKET' => $customer->plan->price,
                'TAGIHAN' => $customer->plan->price,
                'ISOLIR' => $customer->isolir_date,
            ];
            $content = replacePlaceholder($template, $data);
            $notification = new PaymentReminderNotification($customer->phone_number, $content);
            $customer->notify($notification);
        }
    }
}
