<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Afspraak maken') · Salon Knip</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a class="brand" href="{{ route('home') }}">Salon <span>Knip</span></a>
            <nav class="nav">
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home', 'booking.*')])>Afspraak maken</a>
                <a href="{{ route('admin.index') }}" @class(['active' => request()->routeIs('admin.*')])>Beheer</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @if (session('status'))
            <div class="alert success" role="status">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">Afsprakenplanner · demo-project gebouwd met Laravel {{ app()->version() }}</div>
    </footer>
</body>
</html>
