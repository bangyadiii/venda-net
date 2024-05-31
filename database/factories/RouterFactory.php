<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Router>
 */
class RouterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'host' => $this->faker->ipv4,
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
            'last_connected_at' => $this->faker->dateTime,
            'auto_isolir' => $this->faker->boolean,
            'isolir_action' => $this->faker->word,
            'isolir_profile_id' => $this->faker->randomNumber()
        ];
    }
}
