@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-800">{{ __('Dashboard') }}</h1>
        <p class="mt-2 text-gray-600">{{ __("You're logged in!") }}</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Quick Stats Card -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800">Quick Access</h3>
            <p class="mt-2 text-sm text-gray-600">Navigate to your role-specific dashboard.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Admin Dashboard</a>
                    @elseif(Auth::user()->role === 'officer')
                        <a href="{{ route('officer.dashboard') }}" class="px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Officer Dashboard</a>
                    @else
                        <a href="{{ route('voter.dashboard') }}" class="px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Voter Dashboard</a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800">Profile</h3>
            @auth
                <p class="mt-2 text-sm text-gray-600">
                    <strong>Name:</strong> {{ Auth::user()->name }}
                </p>
                <p class="mt-1 text-sm text-gray-600">
                    <strong>Email:</strong> {{ Auth::user()->email }}
                </p>
                <p class="mt-1 text-sm text-gray-600">
                    <strong>Role:</strong> <span class="capitalize font-medium">{{ Auth::user()->role }}</span>
                </p>
                <a href="{{ route('profile.edit') }}" class="inline-block mt-4 px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Edit Profile</a>
            @endauth
        </div>

        <!-- Help Card -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800">Need Help?</h3>
            <p class="mt-2 text-sm text-gray-600">Contact support or visit the documentation.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="#" class="px-3 py-2 border border-gray-300 text-gray-700 rounded text-sm hover:bg-gray-50">Documentation</a>
                <a href="#" class="px-3 py-2 border border-gray-300 text-gray-700 rounded text-sm hover:bg-gray-50">Support</a>
            </div>
        </div>
    </div>
</div>
@endsection
