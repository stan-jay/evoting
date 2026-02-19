<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Left: Logo + Desktop Nav --}}
            <div class="flex items-center space-x-8">
                <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="text-lg font-bold text-blue-600 hover:text-blue-700 transition">
                    {{ config('app.name', 'E-Voting') }}
                </a>

                <div class="hidden lg:flex lg:space-x-1">
                    @auth
                        @php $role = optional(Auth::user())->role; @endphp

                        @if($role === 'super_admin')
                            <a href="{{ route('super_admin.dashboard') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Organizations</a>
                            <a href="{{ route('super_admin.users.index') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Users</a>
                            <a href="{{ route('super_admin.docs.show') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Docs</a>
                        @elseif($role === 'admin')
                            <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Dashboard</a>
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Users</a>
                            <a href="{{ Route::has('admin.elections.index') ? route('admin.elections.index') : url('/admin/elections') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Elections</a>
                            <a href="{{ Route::has('admin.candidates.approval') ? route('admin.candidates.approval') : url('/admin/candidates/approval') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Candidates</a>
                            <a href="{{ Route::has('admin.results.index') ? route('admin.results.index') : url('/admin/results') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Results</a>
                            <a href="{{ route('admin.invites.index') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Invites</a>
                            <a href="{{ Route::has('admin.audit.logs') ? route('admin.audit.logs') : url('/admin/audit') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Audit</a>
                        @elseif($role === 'officer')
                            <a href="{{ Route::has('officer.dashboard') ? route('officer.dashboard') : url('/officer/dashboard') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Dashboard</a>
                            <a href="{{ Route::has('officer.positions.index') ? route('officer.positions.index') : url('/officer/positions') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Positions</a>
                            <a href="{{ Route::has('officer.candidates.create') ? route('officer.candidates.create') : url('/officer/candidates/create') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Add Candidate</a>
                            <a href="{{ Route::has('officer.candidates.index') ? route('officer.candidates.index') : url('/officer/candidates') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">My Candidates</a>
                        @else
                            <a href="{{ Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Dashboard</a>
                            <a href="{{ Route::has('elections.index') ? route('elections.index') : url('/elections') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Elections</a>
                            <a href="{{ Route::has('voter.results.index') ? route('voter.results.index') : url('/results') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Results</a>
                        @endif
                    @endauth

                    @guest
                        <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Home</a>
                    @endguest
                </div>
            </div>

            {{-- Right: Profile + Auth --}}
            <div class="flex items-center space-x-2 sm:space-x-4">
                @auth
                    <div class="hidden sm:flex items-center space-x-3">
                        <span class="text-sm text-gray-700 font-medium truncate max-w-[200px]">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 flex-shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                    @if(Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}" class="hidden sm:inline-block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded transition">Profile</a>
                    @endif

                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded transition">Logout</button>
                    </form>
                @endauth

                @guest
                    <div class="hidden sm:flex sm:space-x-2">
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded transition">Login</a>
                        @endif

                        <span class="px-3 py-2 text-sm text-gray-500">Invite-only signup</span>
                    </div>
                @endguest

                {{-- Mobile Menu Button --}}
                <button @click="open = !open" type="button" class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
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

    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak class="lg:hidden border-t border-gray-100 bg-white max-h-[calc(100vh-64px)] overflow-y-auto">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                <div class="px-3 py-3 flex items-center space-x-2 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 flex-shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</span>
                </div>

                @php $role = optional(Auth::user())->role; @endphp

                @if($role === 'super_admin')
                    <a @click="open = false" href="{{ route('super_admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Organizations</a>
                    <a @click="open = false" href="{{ route('super_admin.users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Users</a>
                    <a @click="open = false" href="{{ route('super_admin.docs.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Docs</a>
                @elseif($role === 'admin')
                    <a @click="open = false" href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Dashboard</a>
                    <a @click="open = false" href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Users</a>
                    <a @click="open = false" href="{{ Route::has('admin.elections.index') ? route('admin.elections.index') : url('/admin/elections') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Elections</a>
                    <a @click="open = false" href="{{ Route::has('admin.candidates.approval') ? route('admin.candidates.approval') : url('/admin/candidates/approval') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Candidates</a>
                    <a @click="open = false" href="{{ Route::has('admin.results.index') ? route('admin.results.index') : url('/admin/results') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Results</a>
                    <a @click="open = false" href="{{ route('admin.invites.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Invites</a>
                    <a @click="open = false" href="{{ Route::has('admin.audit.logs') ? route('admin.audit.logs') : url('/admin/audit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Audit</a>
                @elseif($role === 'officer')
                    <a @click="open = false" href="{{ Route::has('officer.dashboard') ? route('officer.dashboard') : url('/officer/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Dashboard</a>
                    <a @click="open = false" href="{{ Route::has('officer.positions.index') ? route('officer.positions.index') : url('/officer/positions') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Positions</a>
                    <a @click="open = false" href="{{ Route::has('officer.candidates.create') ? route('officer.candidates.create') : url('/officer/candidates/create') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Add Candidate</a>
                    <a @click="open = false" href="{{ Route::has('officer.candidates.index') ? route('officer.candidates.index') : url('/officer/candidates') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">My Candidates</a>
                @else
                    <a @click="open = false" href="{{ Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Dashboard</a>
                    <a @click="open = false" href="{{ Route::has('elections.index') ? route('elections.index') : url('/elections') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Elections</a>
                    <a @click="open = false" href="{{ Route::has('voter.results.index') ? route('voter.results.index') : url('/results') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Results</a>
                @endif

                @if(Route::has('profile.edit'))
                    <a @click="open = false" href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Profile</a>
                @endif

                <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}" @submit="open = false">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition">Logout</button>
                </form>
            @endauth

            @guest
                <a @click="open = false" href="{{ Route::has('home') ? route('home') : url('/') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Home</a>

                @if(Route::has('login'))
                    <a @click="open = false" href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">Login</a>
                @endif

                <span class="block px-3 py-2 text-sm text-gray-500">Signup is invite-only</span>
            @endguest
        </div>
    </div>
</nav>
