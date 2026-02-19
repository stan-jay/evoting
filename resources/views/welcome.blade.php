<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Voting') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen">
        <header class="border-b bg-white/80 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">Evoting</a>
                @guest
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-100">Log in</a>
                        <span class="text-sm text-slate-500">Invite-only signup</span>
                    </div>
                @endguest
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-bold leading-tight">Secure Online Voting</h1>
                <p class="mt-4 text-lg text-slate-600">Transparent. Secure. One voter, one vote.</p>

                @guest
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Log in</a>
                        <span class="text-sm text-slate-500">Ask your institution admin for an invite link.</span>
                    </div>
                @else
                    <div class="mt-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Go to Dashboard</a>
                    </div>
                @endguest
            </div>
        </main>
    </div>
</body>
</html>
