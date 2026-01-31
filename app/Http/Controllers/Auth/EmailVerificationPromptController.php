<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return match ($request->user()->role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'officer' => redirect()->route('officer.dashboard'),
                default   => redirect()->route('voter.dashboard'),
            };
        }

        return view('auth.verify-email');
    }
}
