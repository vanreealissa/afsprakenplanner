@extends('layouts.app')

@section('title', $service->name)

@section('content')
    <p><a href="{{ route('home') }}">← Andere behandeling kiezen</a></p>
    <h1>{{ $service->name }}</h1>
    <p class="lead">{{ $service->duration_minutes }} minuten · {{ $service->formattedPrice() }}</p>

    <section class="card" style="margin-bottom:20px">
        <h2>1. Kies een dag</h2>
        <div class="row">
            @foreach ($days as $day)
                <a href="{{ route('booking.show', [$service, 'datum' => $day->toDateString()]) }}"
                   class="btn small {{ $day->isSameDay($date) ? '' : 'secondary' }}">
                    {{ $day->translatedFormat('D j M') }}
                </a>
            @endforeach
        </div>
    </section>

    <form method="POST" action="{{ route('booking.store', $service) }}" class="card">
        @csrf
        <h2>2. Kies een tijd op {{ $date->translatedFormat('l j F') }}</h2>

        @error('starts_at')
            <div class="alert error" role="alert">{{ $message }}</div>
        @enderror

        @if (! $isOpen)
            <p class="muted">Op deze dag zijn we gesloten. Kies een andere dag.</p>
        @elseif ($slots->isEmpty())
            <p class="muted">Er zijn geen tijden meer vrij op deze dag. Kies een andere dag.</p>
        @else
            @foreach ($slots as $part => $partSlots)
                <p class="muted" style="margin:12px 0 6px">{{ $part }}</p>
                <div class="row">
                    @foreach ($partSlots as $slot)
                        @php($value = $slot->format('Y-m-d H:i'))
                        <label class="btn small secondary" style="font-weight:500">
                            <input type="radio" name="starts_at" value="{{ $value }}" @checked(old('starts_at') === $value) required>
                            {{ $slot->format('H:i') }}
                        </label>
                    @endforeach
                </div>
            @endforeach

            <h2 style="margin-top:28px">3. Jouw gegevens</h2>
            <div class="field">
                <label for="customer_name">Naam</label>
                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" autocomplete="name" required @class(['is-invalid' => $errors->has('customer_name')])>
                @error('customer_name') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="customer_email">E-mailadres</label>
                <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" autocomplete="email" required @class(['is-invalid' => $errors->has('customer_email')])>
                @error('customer_email') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="customer_phone">Telefoonnummer <span class="hint">(optioneel)</span></label>
                <input id="customer_phone" name="customer_phone" type="tel" value="{{ old('customer_phone') }}" autocomplete="tel">
                @error('customer_phone') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="notes">Opmerking <span class="hint">(optioneel)</span></label>
                <textarea id="notes" name="notes" placeholder="Bijvoorbeeld: ik wil graag een korter model.">{{ old('notes') }}</textarea>
                @error('notes') <span class="error">{{ $message }}</span> @enderror
            </div>
            <button class="btn" type="submit">Afspraak bevestigen</button>
        @endif
    </form>
@endsection
