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
<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        <main class="flex-1 flex items-center justify-center py-8 sm:py-12 px-4">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-600">
                {{ config('app.name', 'E-Voting') }} &copy; {{ date('Y') }}
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
