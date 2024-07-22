<?php

namespace App\Console\Commands;

use App\Enums\BillStatus;
use App\Enums\ServiceStatus;
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
    protected $signature = 'app:payment-reminder';

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
        $bank = Setting::query()->where('key', 'rekening')->first()?->value ?? '';

        $customers = Customer::with(['bills', 'plan'])
            ->where('service_status', ServiceStatus::ACTIVE)
            ->withWhereHas('bills', function ($query) {
                $query->whereDate('due_date', now()->addDay())
                    ->where('status', BillStatus::UNPAID)
                    ->where('total_amount', '>', 0);
            })
            ->get();

        \info('total customer yang ditagih : ' . $customers->count());
        if($customers->isEmpty()) {
            $this->info('No customers with due payments found');
            return Command::SUCCESS;
        }

        foreach ($customers as $customer) {
            $data = [
                'NOPEL' => $customer->id,
                'NAMA' => $customer->customer_name,
                'PHONE' => $customer->phone_number,
                'ALAMAT' => $customer->address,
                'PAKET' => $customer->plan->name,
                'TARIFPAKET' => \currency($customer->plan->price),
                'TAGIHAN' => currency($customer->bills->last()->total_amount),
                'ISOLIR' => $customer->isolir_date,
                'BANK' => $bank,
                'PAYMENT_URL' => route('payment.index', ['id' => $customer->id]),
            ];
            $content = replacePlaceholder($template, $data);
            $notification = new PaymentReminderNotification($customer->phone_number, $content);
            $customer->notify($notification);
        }
        return Command::SUCCESS;
    }
}
