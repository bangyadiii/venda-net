<?php

namespace Database\Factories;

use App\Enums\InstallmentStatus;
use App\Enums\ServiceStatus;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $activeDate = $this->faker->dateTimeBetween(\now()->subYears(2), \now());
        return [
            'customer_name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'plan_id' => Plan::factory(),
            'installment_status' => InstallmentStatus::INSTALLED,
            'service_status' => ServiceStatus::ACTIVE,
            'active_date' => $activeDate,
            'isolir_date' => $activeDate->format('d'),
            'secret_id' => $this->faker->randomNumber(),
        ];
    }
}
