<?php

namespace App\Console\Commands;

use App\Enums\BillStatus;
use App\Enums\ServiceStatus;
use Illuminate\Console\Command;
use App\Models\Customer;
use App\Jobs\IsolirCustomerJob;
use Carbon\Carbon;

class CheckLatePayments extends Command
{
    protected $signature = 'app:late-payments';
    protected $description = 'Check for customers with late payments and suspend their service';

    public function handle()
    {
        /** @var \Illuminate\Database\Eloquent\Collection $customers */
        $customers = Customer::query()
            ->where('service_status', ServiceStatus::ACTIVE)
            ->whereHas('plan.router', function ($query) {
                $query->where('auto_isolir', true);
            })
            ->whereHas('bills', function ($query) {
                $query
                    ->where('total_amount', '>', 0)
                    ->where('status', BillStatus::UNPAID)
                    ->where('due_date', '<', Carbon::now());
            })->get();
        if ($customers->isEmpty()) {
            $this->info('No customers with late payments found');
            return Command::SUCCESS;
        }

        foreach ($customers as $customer) {
            \dispatch(new IsolirCustomerJob($customer));
        }

        return Command::SUCCESS;
    }
}
