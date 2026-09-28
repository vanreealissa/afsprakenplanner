<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class BookingController extends Controller
{
    public function index()
    {
        return view('booking.index', [
            'services' => Service::orderBy('price_cents')->get(),
        ]);
    }

    public function show(Request $request, Service $service, AvailabilityService $availability)
    {
        $today = today();
        $date = rescue(fn () => Carbon::createFromFormat('Y-m-d', (string) $request->query('datum'))->startOfDay(), $today, false);

        if ($date->lt($today) || $date->gt($today->copy()->addDays(30))) {
            $date = $today;
        }

        $days = collect(range(0, 13))->map(fn (int $i) => $today->copy()->addDays($i));
        $slots = $availability->slotsFor($service, $date);

        return view('booking.show', [
            'service' => $service,
            'date' => $date,
            'days' => $days,
            'isOpen' => $availability->isOpenOn($date),
            'slots' => $slots->groupBy(fn (Carbon $slot) => $slot->hour < 12 ? 'Ochtend' : ($slot->hour < 17 ? 'Middag' : 'Avond')),
        ]);
    }

    public function store(StoreAppointmentRequest $request, Service $service, AvailabilityService $availability)
    {
        $start = Carbon::createFromFormat('Y-m-d H:i', $request->validated('starts_at'))->second(0);

        if (! $availability->isAvailable($service, $start)) {
            return back()
                ->withInput()
                ->withErrors(['starts_at' => 'Dit tijdstip is helaas niet (meer) beschikbaar. Kies een ander tijdstip.']);
        }

        $appointment = $service->appointments()->create([
            ...$request->safe()->except('starts_at'),
            'starts_at' => $start,
            'ends_at' => $start->copy()->addMinutes($service->duration_minutes),
        ]);

        return redirect(URL::signedRoute('booking.confirmation', $appointment));
    }

    public function confirmation(Appointment $appointment)
    {
        return view('booking.confirmation', ['appointment' => $appointment->load('service')]);
    }
}
