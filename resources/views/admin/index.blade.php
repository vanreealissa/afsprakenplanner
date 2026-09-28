@extends('layouts.app')

@section('title', 'Beheer')

@section('content')
    <div class="row between" style="margin-bottom:20px">
        <div>
            <h1>Agenda</h1>
            <p class="muted" style="margin:0">{{ $date->translatedFormat('l j F Y') }}</p>
        </div>
        <div class="row">
            <a class="btn secondary small" href="{{ route('admin.index', ['datum' => $date->copy()->subDay()->toDateString()]) }}">← Vorige dag</a>
            <a class="btn secondary small" href="{{ route('admin.index') }}">Vandaag</a>
            <a class="btn secondary small" href="{{ route('admin.index', ['datum' => $date->copy()->addDay()->toDateString()]) }}">Volgende dag →</a>
        </div>
    </div>

    <div class="grid" style="margin-bottom:20px">
        <div class="card"><p class="muted" style="margin:0">Afspraken</p><strong style="font-size:1.8rem" class="num">{{ $appointments->count() }}</strong></div>
        <div class="card"><p class="muted" style="margin:0">Verwachte omzet</p><strong style="font-size:1.8rem" class="num">€ {{ number_format($revenue / 100, 2, ',', '.') }}</strong></div>
    </div>

    <div class="card table-wrap">
        @if ($appointments->isEmpty())
            <p class="muted" style="margin:0">Geen afspraken op deze dag.</p>
        @else
            <table>
                <thead>
                    <tr><th>Tijd</th><th>Klant</th><th>Behandeling</th><th>Opmerking</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td class="num">{{ $appointment->starts_at->format('H:i') }}–{{ $appointment->ends_at->format('H:i') }}</td>
                            <td>
                                {{ $appointment->customer_name }}<br>
                                <span class="hint">{{ $appointment->customer_email }} {{ $appointment->customer_phone }}</span>
                            </td>
                            <td>{{ $appointment->service->name }}</td>
                            <td class="hint">{{ $appointment->notes }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.destroy', $appointment) }}" onsubmit="return confirm('Deze afspraak annuleren?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" type="submit">Annuleren</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
