<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $start = now()->addDay()->setTime(10, 0);

        return [
            'service_id' => Service::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => fake()->optional()->phoneNumber(),
            'starts_at' => $start,
            'ends_at' => $start->copy()->addMinutes(30),
            'notes' => null,
        ];
    }
}
