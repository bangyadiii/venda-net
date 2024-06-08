<?php

namespace App\Console\Commands;

use App\Enums\ServiceStatus;
use App\Jobs\GenerateMonthlyBills;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-bill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customers = Customer::with('plan')
            ->where('service_status', ServiceStatus::ACTIVE)
            ->whereDoesntHave('bills', function ($query) {
                $query->where(DB::raw('MONTH(due_date)'), now()->month);
            })
            ->get();
        /**
         * @var Customer $customer
         */
        foreach ($customers as $customer) {
            dispatch(new GenerateMonthlyBills($customer));
        }
    }
}
