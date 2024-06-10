<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = Plan::all();

        $customers = Customer::factory()
            ->recycle($plans)
            ->count(5)
            ->create();

        foreach ($customers as $customer) {
            Bill::factory()
                ->sequence(function ($sequence) use ($customer) {
                    return [
                        'customer_id' => $customer->id,
                        'plan_id' => $customer->plan->id,
                        'discount' => 0,
                        'total_amount' => $customer->plan->price * 1.11,
                    ];
                })
                ->create();
        }
    }
}
