<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use App\Models\Vote;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $elections = Election::count();
        $activeElections = Election::where('status', 'active')->count();
        $totalVoters = User::where('role', 'voter')->count();
        $totalCandidates = Candidate::count();
        $totalVotes = Vote::count();

        return view('admin.dashboard', compact(
            'elections',
            'activeElections',
            'totalVoters',
            'totalCandidates',
            'totalVotes'
        ));
    }
}
