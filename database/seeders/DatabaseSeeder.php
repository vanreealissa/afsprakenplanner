<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Service;
use App\Services\AvailabilityService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $services = collect([
            ['name' => 'Knippen', 'description' => 'Wassen, knippen en stylen.', 'duration_minutes' => 30, 'price_cents' => 3250],
            ['name' => 'Knippen en föhnen', 'description' => 'Inclusief uitgebreid föhnen en advies.', 'duration_minutes' => 60, 'price_cents' => 4500],
            ['name' => 'Kleuren', 'description' => 'Uitgroei of volledige kleuring.', 'duration_minutes' => 90, 'price_cents' => 7900],
            ['name' => 'Baard trimmen', 'description' => 'Baard in model, met hete handdoek.', 'duration_minutes' => 30, 'price_cents' => 1800],
        ])->map(fn (array $data) => Service::create($data));

        // Een paar voorbeeldafspraken in de komende dagen, zodat de agenda niet leeg is.
        $day = today();
        $placed = 0;

        while ($placed < 12) {
            $day->addDay();

            if (! isset(AvailabilityService::OPENING_HOURS[$day->dayOfWeekIso])) {
                continue;
            }

            foreach (['09:30', '11:00', '14:00', '15:30'] as $time) {
                $service = $services->random();
                $start = $day->copy()->setTimeFromTimeString($time);

                Appointment::factory()->create([
                    'service_id' => $service->id,
                    'starts_at' => $start,
                    'ends_at' => $start->copy()->addMinutes($service->duration_minutes),
                ]);
                $placed++;
            }
        }
    }
}
