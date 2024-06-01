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

        Customer::factory()
            ->recycle($plans)
            ->count(10)
            ->has(
                Bill::factory()
                    ->count(3)
                    ->has(
                        Payment::factory()
                            ->count(3)
                    )
            )
            ->create();
    }
}
