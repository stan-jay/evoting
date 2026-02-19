<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OrganizationInvite;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(string $token)
    {
        $invite = OrganizationInvite::query()
            ->withoutGlobalScope('organization')
            ->validToken($token)
            ->firstOrFail();

        return view('auth.register', compact('invite'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, string $token)
    {
        $invite = OrganizationInvite::query()
            ->withoutGlobalScope('organization')
            ->validToken($token)
            ->first();

        if (! $invite) {
            throw ValidationException::withMessages([
                'email' => 'This invite is invalid or has expired.',
            ]);
        }

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'terms'                 => ['required', 'accepted'],
        ], [
            'email.unique'          => 'This email address is already registered.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Password confirmation does not match.',
            'terms.accepted'        => 'You must accept the terms and conditions.',
        ]);

        if (strtolower($validated['email']) !== strtolower($invite->email)) {
            throw ValidationException::withMessages([
                'email' => 'This email does not match the invite.',
            ]);
        }

        try {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'organization_id' => $invite->organization_id,
                'role'     => $invite->role ?: 'voter',
                'status'   => 'active',
            ]);

            $invite->accepted_at = now();
            $invite->save();

            event(new Registered($user));

            Auth::login($user);

            return redirect()->route('dashboard')
                ->with('success', 'Registration successful! Welcome to ' . config('app.name', 'E-Voting'));
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors([
                    'registration' => 'An error occurred during registration. Please try again.',
                ]);
        }
    }
}
