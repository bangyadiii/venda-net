<?php

namespace Database\Factories;

use App\Enums\BillStatus;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(),
            'customer_id' => $this->faker->randomNumber(),
            'plan_id' => Plan::factory(),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'tax_rate' => $this->faker->randomFloat(2, 0, 100),
            'total_amount' => $this->faker->randomFloat(2, 0, 100000),
            'due_date' => $this->faker->dateTime,
            'status' => $this->faker->randomElement([BillStatus::PAID, BillStatus::UNPAID]),
        ];
    }
}
