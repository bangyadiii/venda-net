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
            'discount' => 0,
            'tax_rate' => 11,
            'total_amount' => $this->faker->randomFloat(2, 0, 100_000),
            'due_date' => $this->faker->dateTimeBetween(\now()->subYears(2), \now()),
            'status' => BillStatus::UNPAID,
        ];
    }
}
