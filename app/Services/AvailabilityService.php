<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /** Openingstijden per ISO-weekdag (1 = maandag). Zondag is gesloten. */
    public const OPENING_HOURS = [
        1 => ['09:00', '17:30'],
        2 => ['09:00', '17:30'],
        3 => ['09:00', '17:30'],
        4 => ['09:00', '20:00'],
        5 => ['09:00', '17:30'],
        6 => ['09:00', '15:00'],
    ];

    /** Om de hoeveel minuten een afspraak kan beginnen. */
    public const SLOT_INTERVAL = 15;

    public function isOpenOn(Carbon $date): bool
    {
        return isset(self::OPENING_HOURS[$date->dayOfWeekIso]);
    }

    /**
     * Alle begintijden op $date waarop $service nog past.
     *
     * @return Collection<int, Carbon>
     */
    public function slotsFor(Service $service, Carbon $date): Collection
    {
        if (! $this->isOpenOn($date)) {
            return collect();
        }

        [$openTime, $closeTime] = self::OPENING_HOURS[$date->dayOfWeekIso];
        $open = $date->copy()->setTimeFromTimeString($openTime);
        $close = $date->copy()->setTimeFromTimeString($closeTime);

        $booked = Appointment::query()->overlapping($open, $close)->get(['starts_at', 'ends_at']);
        $now = now();
        $slots = collect();

        for ($start = $open->copy(); $start->copy()->addMinutes($service->duration_minutes)->lte($close); $start->addMinutes(self::SLOT_INTERVAL)) {
            if ($start->lte($now)) {
                continue;
            }

            $end = $start->copy()->addMinutes($service->duration_minutes);
            $clashes = $booked->contains(fn (Appointment $a) => $a->starts_at->lt($end) && $a->ends_at->gt($start));

            if (! $clashes) {
                $slots->push($start->copy());
            }
        }

        return $slots;
    }

    public function isAvailable(Service $service, Carbon $start): bool
    {
        return $this->slotsFor($service, $start->copy()->startOfDay())
            ->contains(fn (Carbon $slot) => $slot->equalTo($start));
    }
}
