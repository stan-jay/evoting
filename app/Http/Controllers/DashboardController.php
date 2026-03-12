<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = auth()->user();

        $target = match ($user?->role) {
            'super_admin' => Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : url('/superadmin/dashboard'),
            'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard'),
            'officer' => Route::has('officer.dashboard') ? route('officer.dashboard') : url('/officer/dashboard'),
            default => Route::has('voter.dashboard') ? route('voter.dashboard') : url('/voter/dashboard'),
        };

        return redirect()->to($target);
    }
}
