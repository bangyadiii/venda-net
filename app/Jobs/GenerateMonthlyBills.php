<?php

namespace App\Jobs;

use App\Enums\BillStatus;
use App\Enums\ServiceStatus;
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
    public function __construct(private Customer $customer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->customer->plan) {
            $isolir = Carbon::createFromDate(now()->year, now()->month, $this->customer->isolir_date);
            if ($isolir->isPast()) {
                $isolir->addMonth();
            }

            $bill = Bill::query()
                ->where('customer_id', $this->customer->id)
                ->where(DB::raw('DATE(due_date)'), $isolir)
                ->first();

            if (!$bill) {
                $tax = (int) Setting::where('key', 'ppn')->first()->value ?? 0;
                Bill::create([
                    'customer_id' => $this->customer->id,
                    'discount' => 0,
                    'tax_rate' => $tax,
                    'total_amount' => $this->customer->plan->price * (1 + $tax / 100),
                    'status' => BillStatus::UNPAID,
                    'due_date' => $isolir,
                    'plan_id' => $this->customer->plan_id,
                ]);
            }
        }
    }
}
