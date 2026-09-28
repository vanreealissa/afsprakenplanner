<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $date = rescue(fn () => Carbon::createFromFormat('Y-m-d', (string) $request->query('datum'))->startOfDay(), today(), false);

        $appointments = Appointment::with('service')
            ->whereBetween('starts_at', [$date, $date->copy()->endOfDay()])
            ->orderBy('starts_at')
            ->get();

        return view('admin.index', [
            'date' => $date,
            'appointments' => $appointments,
            'revenue' => $appointments->sum(fn (Appointment $a) => $a->service->price_cents),
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return back()->with('status', "De afspraak van {$appointment->customer_name} is geannuleerd.");
    }
}
