<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return match ($request->user()->role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'officer' => redirect()->route('officer.dashboard'),
                default   => redirect()->route('voter.dashboard'),
            };
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return match ($request->user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'officer' => redirect()->route('officer.dashboard'),
            default   => redirect()->route('voter.dashboard'),
        };
    }
}
