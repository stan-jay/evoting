@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-800">Profile Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your account information and security.</p>
    </div>

    <!-- Update Profile Information -->
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Information</h2>

        @if(session('status') === 'profile-updated')
            <div class="rounded-md bg-green-50 border border-green-100 p-4 text-sm text-green-800 mb-4">
                Your profile has been updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700" />
                <x-text-input
                    id="name"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                    type="text"
                    name="name"
                    :value="old('name', $user->name)"
                    required
                    autofocus
                    autocomplete="name"
                />
                <x-input-error :messages="$errors->updateProfile->get('name')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700" />
                <x-text-input
                    id="email"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                    type="email"
                    name="email"
                    :value="old('email', $user->email)"
                    required
                    autocomplete="email"
                />
                <x-input-error :messages="$errors->updateProfile->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Current Role (Display Only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Current Role</label>
                <div class="mt-1 inline-block px-3 py-2 bg-gray-100 text-gray-800 rounded text-sm font-medium capitalize">
                    {{ $user->role }}
                </div>
                <p class="mt-2 text-xs text-gray-500">Contact an admin to change your role.</p>
            </div>

            <!-- Current Status (Display Only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Account Status</label>
                <div class="mt-1 inline-block px-3 py-2 rounded text-sm font-medium capitalize {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->status }}
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
            >
                {{ __('Update Profile') }}
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>

        @if(session('status') === 'password-updated')
            <div class="rounded-md bg-green-50 border border-green-100 p-4 text-sm text-green-800 mb-4">
                Your password has been updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <x-input-label for="current_password" :value="__('Current Password')" class="block text-sm font-medium text-gray-700" />
                <x-text-input
                    id="current_password"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                    type="password"
                    name="current_password"
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- New Password -->
            <div>
                <x-input-label for="password" :value="__('New Password')" class="block text-sm font-medium text-gray-700" />
                <x-text-input
                    id="password"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700" />
                <x-text-input
                    id="password_confirmation"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
            >
                {{ __('Update Password') }}
            </button>
        </form>
    </div>

    <!-- Delete Account (Optional) -->
    <div class="bg-white shadow sm:rounded-lg p-6 border-l-4 border-red-500">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Danger Zone</h2>
        <p class="text-sm text-gray-600 mb-4">Contact an administrator to delete your account or request data export.</p>
    </div>
</div>
@endsection
