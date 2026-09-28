@extends('layouts.app')

@section('content')
    <h1>Maak een afspraak</h1>
    <p class="lead">Kies een behandeling, daarna een dag en tijd die jou uitkomt. Je krijgt direct een bevestiging.</p>

    <div class="grid">
        @foreach ($services as $service)
            <article class="card stack">
                <div>
                    <h2>{{ $service->name }}</h2>
                    <p class="muted" style="margin:0">{{ $service->description }}</p>
                </div>
                <div class="row between">
                    <span class="badge">{{ $service->duration_minutes }} min</span>
                    <strong class="num">{{ $service->formattedPrice() }}</strong>
                </div>
                <a class="btn" href="{{ route('booking.show', $service) }}">Kies tijdstip</a>
            </article>
        @endforeach
    </div>
@endsection
