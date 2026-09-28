@extends('layouts.app')

@section('title', 'Afspraak bevestigd')

@section('content')
    <div class="card stack" style="max-width:560px">
        <span class="badge" style="justify-self:start">Bevestigd</span>
        <h1>Tot snel, {{ $appointment->customer_name }}!</h1>
        <p style="margin:0">
            Je afspraak voor <strong>{{ $appointment->service->name }}</strong> staat op
            <strong>{{ $appointment->starts_at->translatedFormat('l j F') }}</strong>
            van <strong>{{ $appointment->starts_at->format('H:i') }}</strong> tot {{ $appointment->ends_at->format('H:i') }}.
        </p>
        <p class="muted" style="margin:0">We sturen een bevestiging naar {{ $appointment->customer_email }}. Verhinderd? Laat het ons minimaal 24 uur van tevoren weten.</p>
        <a class="btn secondary" href="{{ route('home') }}" style="justify-self:start">Nog een afspraak maken</a>
    </div>
@endsection
