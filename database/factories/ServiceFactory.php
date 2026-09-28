<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Knippen', 'Kleuren', 'Föhnen', 'Baard trimmen']),
            'description' => fake()->sentence(),
            'duration_minutes' => 30,
            'price_cents' => fake()->numberBetween(1500, 9000),
        ];
    }
}
