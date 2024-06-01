<?php

namespace Database\Factories;

use App\Enums\InstallmentStatus;
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
        return [
            'customer_name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'plan_id' => Plan::factory(),
            'installment_status' => $this->faker->randomElement([InstallmentStatus::INSTALLED, InstallmentStatus::NOT_INSTALLED]),
            'service_status' => $this->faker->randomElement(['active', 'inactive']),
            'active_date' => $this->faker->dateTime,
            'isolir_date' => $this->faker->dateTime,
            'secret_id' => $this->faker->randomNumber(),
        ];
    }
}
