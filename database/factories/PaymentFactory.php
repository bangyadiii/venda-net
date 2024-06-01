<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bill_id' => Bill::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 100000),
            'status' => $this->faker->randomElement([PaymentStatus::PENDING, PaymentStatus::SUCCESS]),
            'method' => $this->faker->randomElement([PaymentMethod::CASH, PaymentMethod::MIDTRANS]),
            'payment_date' => $this->faker->dateTime,

        ];
    }
}
