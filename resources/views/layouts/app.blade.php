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

        <main class="flex-1 py-6 sm:py-8">
            <div class="page-wrap">
                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
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
