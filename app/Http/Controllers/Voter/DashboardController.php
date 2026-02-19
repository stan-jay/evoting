<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $elections = Election::query()
            ->whereIn('status', ['active', 'pending', 'closed', 'declared'])
            ->orderBy('start_time', 'asc')
            ->get();

        return view('voter.dashboard', compact('elections'));
    }
}
