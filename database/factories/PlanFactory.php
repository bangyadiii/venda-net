<?php

namespace Database\Factories;

use App\Models\Router;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ppp_profile_id' => $this->faker->randomNumber(),
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 200_000),
            'speed_limit' => $this->faker->randomNumber(1) . 'M' . '/' . $this->faker->randomNumber(1) . 'M',
            'router_id' => Router::factory()
        ];
    }
}
