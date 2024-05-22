<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyBills implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = Customer::with('plan')
            ->where('service_status', 'active')
            ->whereDoesntHave('bills', function ($query) {
                $query->where(DB::raw('MONTH(due_date)'), now()->month);
            })
            ->get();

        foreach ($customers as $customer) {
            if ($customer->plan) {
                $isolir = Carbon::createFromDate(now()->year, now()->month, $customer->isolir_date);

                $bill = Bill::query()
                    ->where('customer_id', $customer->id)
                    ->where(DB::raw('DATE(due_date)'), $isolir)
                    ->first();

                if (!$bill) {
                    $tax = (int) Setting::where('key', 'ppn')->first()->value ?? 0;
                    Bill::create([
                        'customer_id' => $customer->id,
                        'discount' => 0,
                        'tax_rate' => $tax,
                        'total_amount' => $customer->plan->price * (1 + $tax / 100),
                        'status' => 'unpaid',
                        'due_date' => $isolir,
                        'plan_id' => $customer->plan_id,
                    ]);
                }
            }
        }
    }
}
