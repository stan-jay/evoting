<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Election;
use App\Models\Vote;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::count();
        $elections = Election::count();
        $activeElections = Election::where('status', 'active')->count();
        $totalVotes = Vote::count();

        $latestClosedElection = Election::where('status', 'closed')
            ->latest()
            ->first();

        return view('admin.dashboard', compact(
            'users',
            'elections',
            'activeElections',
            'totalVotes',
            'latestClosedElection'
        ));
    }
}
