<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="page-wrap">
        <div class="flex min-h-[4.5rem] items-center justify-between gap-6">
            <div class="flex items-center gap-8">
                <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="flex items-center gap-3 text-base font-semibold text-slate-900">
                    <x-application-logo size="36" class="border border-slate-300" />
                    <div>
                        <span class="block leading-none">{{ config('app.name', 'E-Voting') }}</span>
                        <span class="mt-1 block text-xs font-medium uppercase tracking-[0.18em] text-slate-500">Institutional Voting</span>
                    </div>
                </a>

                <div class="hidden lg:flex lg:items-center lg:gap-1">
                    @auth
                        @php $role = optional(Auth::user())->role; @endphp

                        @if($role === 'super_admin')
                            <a href="{{ route('super_admin.dashboard') }}" class="btn-tertiary">Organizations</a>
                            <a href="{{ route('super_admin.users.index') }}" class="btn-tertiary">Users</a>
                            <a href="{{ route('super_admin.docs.show') }}" class="btn-tertiary">Docs</a>
                        @elseif($role === 'admin')
                            <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard') }}" class="btn-tertiary">Dashboard</a>
                            <a href="{{ route('admin.users.index') }}" class="btn-tertiary">Users</a>
                            <a href="{{ Route::has('admin.elections.index') ? route('admin.elections.index') : url('/admin/elections') }}" class="btn-tertiary">Elections</a>
                            <a href="{{ Route::has('admin.candidates.approval') ? route('admin.candidates.approval') : url('/admin/candidates/approval') }}" class="btn-tertiary">Candidates</a>
                            <a href="{{ Route::has('admin.results.index') ? route('admin.results.index') : url('/admin/results') }}" class="btn-tertiary">Results</a>
                            <a href="{{ route('admin.invites.index') }}" class="btn-tertiary">Invites</a>
                            <a href="{{ Route::has('admin.audit.logs') ? route('admin.audit.logs') : url('/admin/audit') }}" class="btn-tertiary">Audit</a>
                        @elseif($role === 'officer')
                            <a href="{{ Route::has('officer.dashboard') ? route('officer.dashboard') : url('/officer/dashboard') }}" class="btn-tertiary">Dashboard</a>
                            <a href="{{ Route::has('officer.positions.index') ? route('officer.positions.index') : url('/officer/positions') }}" class="btn-tertiary">Positions</a>
                            <a href="{{ Route::has('officer.candidates.create') ? route('officer.candidates.create') : url('/officer/candidates/create') }}" class="btn-tertiary">Add Candidate</a>
                            <a href="{{ Route::has('officer.candidates.index') ? route('officer.candidates.index') : url('/officer/candidates') }}" class="btn-tertiary">My Candidates</a>
                        @else
                            <a href="{{ Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard') }}" class="btn-tertiary">Dashboard</a>
                            <a href="{{ route('voter.vote.index') }}" class="btn-tertiary">Elections</a>
                            <a href="{{ route('voter.results.index') }}" class="btn-tertiary">Results</a>
                        @endif
                    @endauth

                    @guest
                        <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="btn-tertiary">Home</a>
                    @endguest
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <div class="hidden sm:flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-800">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <span class="block truncate text-sm font-semibold text-slate-800 max-w-[180px]">{{ Auth::user()->name }}</span>
                            <span class="block text-xs uppercase tracking-[0.15em] text-slate-500">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                        </div>
                    </div>

                    @if(Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}" class="btn-secondary hidden sm:inline-flex">Profile</a>
                    @endif

                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="btn-danger">Logout</button>
                    </form>
                @endauth

                @guest
                    <div class="hidden sm:flex items-center gap-2">
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                        @endif
                        <span class="text-sm text-slate-500">Invite-only signup</span>
                    </div>
                @endguest

                <button @click="open = !open" type="button" class="lg:hidden inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-700">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak class="lg:hidden border-t border-slate-200 bg-white">
        <div class="page-wrap space-y-1 py-3">
            @auth
                <div class="mb-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs uppercase tracking-[0.15em] text-slate-500">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                </div>

                @php $role = optional(Auth::user())->role; @endphp

                @if($role === 'super_admin')
                    <a @click="open = false" href="{{ route('super_admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Organizations</a>
                    <a @click="open = false" href="{{ route('super_admin.users.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Users</a>
                    <a @click="open = false" href="{{ route('super_admin.docs.show') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Docs</a>
                @elseif($role === 'admin')
                    <a @click="open = false" href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Dashboard</a>
                    <a @click="open = false" href="{{ route('admin.users.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Users</a>
                    <a @click="open = false" href="{{ Route::has('admin.elections.index') ? route('admin.elections.index') : url('/admin/elections') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Elections</a>
                    <a @click="open = false" href="{{ Route::has('admin.candidates.approval') ? route('admin.candidates.approval') : url('/admin/candidates/approval') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Candidates</a>
                    <a @click="open = false" href="{{ Route::has('admin.results.index') ? route('admin.results.index') : url('/admin/results') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Results</a>
                    <a @click="open = false" href="{{ route('admin.invites.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Invites</a>
                    <a @click="open = false" href="{{ Route::has('admin.audit.logs') ? route('admin.audit.logs') : url('/admin/audit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Audit</a>
                @elseif($role === 'officer')
                    <a @click="open = false" href="{{ Route::has('officer.dashboard') ? route('officer.dashboard') : url('/officer/dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Dashboard</a>
                    <a @click="open = false" href="{{ Route::has('officer.positions.index') ? route('officer.positions.index') : url('/officer/positions') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Positions</a>
                    <a @click="open = false" href="{{ Route::has('officer.candidates.create') ? route('officer.candidates.create') : url('/officer/candidates/create') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Add Candidate</a>
                    <a @click="open = false" href="{{ Route::has('officer.candidates.index') ? route('officer.candidates.index') : url('/officer/candidates') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">My Candidates</a>
                @else
                    <a @click="open = false" href="{{ Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Dashboard</a>
                    <a @click="open = false" href="{{ route('voter.vote.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Elections</a>
                    <a @click="open = false" href="{{ route('voter.results.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Results</a>
                @endif

                @if(Route::has('profile.edit'))
                    <a @click="open = false" href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Profile</a>
                @endif

                <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}" @submit="open = false" class="pt-2">
                    @csrf
                    <button type="submit" class="btn-danger w-full">Logout</button>
                </form>
            @endauth

            @guest
                <a @click="open = false" href="{{ Route::has('home') ? route('home') : url('/') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Home</a>
                @if(Route::has('login'))
                    <a @click="open = false" href="{{ route('login') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Login</a>
                @endif
            @endguest
        </div>
    </div>
</nav>
