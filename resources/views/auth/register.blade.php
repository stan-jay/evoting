<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    {{ config('app.name', 'E-Voting') }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Complete your invited account setup
                </p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There was an error with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register', ['token' => $invite->token]) }}" class="mt-8 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="name"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="email"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                        type="email"
                        name="email"
                        :value="old('email', $invite->email)"
                        required
                        autocomplete="username"
                        readonly
                        placeholder="name@example.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="********"
                    />
                    <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="password_confirmation"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="********"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start">
                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        required
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-600">
                        {{ __('I agree to the') }}
                        <a href="#" class="text-blue-600 hover:text-blue-500">{{ __('Terms and Conditions') }}</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                >
                    {{ __('Create Account') }}
                </button>
            </form>

            <!-- Sign In Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Invite for: <span class="font-semibold">{{ $invite->organization->name ?? 'Organization' }}</span>
            </div>

            <!-- Home Link -->
            <div class="text-center">
                <a href="{{ route('home') ?? url('/') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    &larr; {{ __('Back to Home') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>

