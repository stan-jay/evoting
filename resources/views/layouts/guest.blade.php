<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'E-Voting') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/sjs-logo-white.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/sjs-logo-white.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="app-shell">
        @include('layouts.navigation')

        <main class="flex-1 flex items-center py-8 sm:py-12">
            <div class="page-wrap w-full">
                {{ $slot }}
            </div>
        </main>

        <footer class="border-t border-slate-200 bg-white/90 py-4">
            <div class="page-wrap text-center text-sm text-slate-500">
                {{ config('app.name', 'E-Voting') }} &copy; {{ date('Y') }}
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
