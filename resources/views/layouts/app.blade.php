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
@php
    $role = auth()->user()->role ?? 'voter';

    $menuItems = match ($role) {
        'admin' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['key' => 'elections', 'label' => 'Elections', 'url' => route('admin.elections.index')],
            ['key' => 'candidates', 'label' => 'Candidates', 'url' => route('admin.candidates.index')],
            ['key' => 'voters', 'label' => 'Voters', 'url' => route('admin.users.index')],
            ['key' => 'results', 'label' => 'Results', 'url' => route('admin.results.index')],
            ['key' => 'settings', 'label' => 'Settings', 'url' => route('profile.edit')],
        ],
        'officer' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'url' => route('officer.dashboard')],
            ['key' => 'elections', 'label' => 'Positions', 'url' => route('officer.positions.index')],
            ['key' => 'candidates', 'label' => 'Candidates', 'url' => route('officer.candidates.index')],
            ['key' => 'results', 'label' => 'Results', 'url' => route('officer.dashboard')],
            ['key' => 'settings', 'label' => 'Settings', 'url' => route('profile.edit')],
        ],
        default => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'url' => route('voter.dashboard')],
            ['key' => 'elections', 'label' => 'Elections', 'url' => route('voter.vote.index')],
            ['key' => 'candidates', 'label' => 'Candidates', 'url' => route('voter.vote.index')],
            ['key' => 'results', 'label' => 'Results', 'url' => route('voter.results.index')],
            ['key' => 'settings', 'label' => 'Settings', 'url' => route('profile.edit')],
        ],
    };

    $mobileItems = collect($menuItems)->take(5)->values()->all();
@endphp

<div
    class="min-h-screen bg-slate-100"
    x-data="dashboardShell()"
    x-init="init()"
>
    <div class="flex min-h-screen">
        <aside
            class="hidden md:flex md:flex-col border-r border-slate-200 bg-white/95 backdrop-blur transition-all duration-200"
            :class="sidebarOpen ? 'w-64' : 'w-20'"
        >
            <div class="flex h-16 items-center justify-between border-b border-slate-200 px-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <x-application-logo size="32" class="border border-slate-300" />
                    <span x-show="sidebarOpen" class="text-sm font-semibold text-slate-800">Evoting</span>
                </a>
                <button type="button" class="rounded-lg border border-slate-200 p-1.5 text-slate-600" @click="sidebarOpen = !sidebarOpen">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                @foreach($menuItems as $item)
                    <a
                        href="{{ $item['url'] }}"
                        @click.prevent="navigate('{{ $item['url'] }}')"
                        class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition"
                        :class="isActive('{{ $item['url'] }}') ? 'bg-blue-50 text-blue-800' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                    >
                        <span class="inline-flex h-5 w-5 items-center justify-center">
                            @if($item['key'] === 'dashboard')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
                            @elseif($item['key'] === 'elections')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z"/></svg>
                            @elseif($item['key'] === 'candidates')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm8 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5c0-2.33-4.67-3.5-7-3.5zM8 13c-.29 0-.62.02-.97.05C5.59 13.24 2 14 2 16.5V20h5v-3.5c0-1.34.86-2.5 2.19-3.36A13.63 13.63 0 008 13z"/></svg>
                            @elseif($item['key'] === 'voters')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-4a3 3 0 00-3-3H10a3 3 0 00-3 3v4m10 0H7m5-9a3 3 0 100-6 3 3 0 000 6z"/></svg>
                            @elseif($item['key'] === 'results')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.983 5.5a2.5 2.5 0 014.034 2.83l-.25.466a2 2 0 001.52 2.9l.52.07a2.5 2.5 0 011.056 4.53l-.443.321a2 2 0 00-.73 2.795l.275.446a2.5 2.5 0 01-3.709 3.243l-.42-.352a2 2 0 00-2.88.336l-.31.43a2.5 2.5 0 01-4.542-.84l-.11-.5a2 2 0 00-2.42-1.48l-.5.12a2.5 2.5 0 01-2.85-3.72l.26-.45a2 2 0 00-.74-2.78l-.45-.27A2.5 2.5 0 012.78 8.66l.5-.11a2 2 0 001.48-2.42l-.12-.5a2.5 2.5 0 013.72-2.85l.45.26a2 2 0 002.79-.73l.31-.44z"/></svg>
                            @endif
                        </span>
                        <span x-show="sidebarOpen" x-transition>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8 gap-4">
                    <div class="flex items-center gap-3">
                        <button type="button" class="md:hidden rounded-lg border border-slate-200 p-2 text-slate-600" @click="mobileMenu = !mobileMenu">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <a href="{{ route('home') }}" class="md:hidden flex items-center gap-2 text-sm font-semibold text-slate-800">
                            <x-application-logo size="28" class="border border-slate-300" /> Evoting
                        </a>
                    </div>

                    <div class="hidden md:block flex-1 max-w-xl">
                        <label class="relative block">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="search" placeholder="Search modules..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-700 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </label>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" class="relative rounded-lg border border-slate-200 p-2 text-slate-600 hover:bg-slate-50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z"/></svg>
                            <span class="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full bg-red-500"></span>
                        </button>

                        <div x-data="{ open:false }" class="relative">
                            <button type="button" class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-2 py-1.5" @click="open = !open">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-800">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                                    <p class="text-xs uppercase tracking-[0.15em] text-slate-500">{{ str_replace('_',' ', $role) }}</p>
                                </div>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-44 rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                                <a href="{{ route('profile.edit') }}" @click.prevent="navigate('{{ route('profile.edit') }}'); open=false" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile / Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-700 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="mobileMenu" class="md:hidden border-t border-slate-200 px-4 py-3">
                    <div class="space-y-1">
                        @foreach($menuItems as $item)
                            <a href="{{ $item['url'] }}" @click.prevent="mobileMenu=false; navigate('{{ $item['url'] }}')" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden pb-20 md:pb-6">
                <div class="px-4 py-6 sm:px-6 lg:px-8">
                    <div id="app-content">
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
                </div>
            </main>
        </div>
    </div>

    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white md:hidden">
        <div class="grid grid-cols-5">
            @foreach($mobileItems as $item)
                <a href="{{ $item['url'] }}" @click.prevent="navigate('{{ $item['url'] }}')" class="flex flex-col items-center justify-center gap-1 py-2 text-[11px] font-medium"
                   :class="isActive('{{ $item['url'] }}') ? 'text-blue-800' : 'text-slate-500'">
                    <span class="h-1.5 w-1.5 rounded-full" :class="isActive('{{ $item['url'] }}') ? 'bg-blue-700' : 'bg-slate-300'"></span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
</div>

<script>
function dashboardShell() {
    return {
        sidebarOpen: true,
        mobileMenu: false,
        loading: false,
        init() {
            window.addEventListener('popstate', () => {
                this.navigate(window.location.href, false);
            });
        },
        isActive(url) {
            try {
                const current = window.location.pathname.replace(/\/$/, '');
                const target = new URL(url, window.location.origin).pathname.replace(/\/$/, '');
                return current === target;
            } catch (_) {
                return false;
            }
        },
        async navigate(url, push = true) {
            if (this.loading) return;
            this.loading = true;
            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    window.location.href = url;
                    return;
                }

                const html = await response.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const nextContent = doc.querySelector('#app-content');

                if (!nextContent) {
                    window.location.href = url;
                    return;
                }

                const currentContent = document.querySelector('#app-content');
                currentContent.innerHTML = nextContent.innerHTML;

                if (push) {
                    history.pushState({}, '', url);
                }

                document.title = doc.title || document.title;
                window.scrollTo({ top: 0, behavior: 'smooth' });

                if (window.Alpine) {
                    window.Alpine.initTree(currentContent);
                }
            } catch (_) {
                window.location.href = url;
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

@stack('scripts')
</body>
</html>
