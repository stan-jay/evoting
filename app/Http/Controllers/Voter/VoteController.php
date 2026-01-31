<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function dashboard()
    {
        $elections = Election::orderBy('start_time')->get();

        return view('voter.dashboard', compact('elections'));
    }

    public function show(Election $election)
    {
        if ($election->status !== 'active') {
            return redirect()
                ->route('voter.dashboard')
                ->with('error', 'Voting is not active for this election.');
        }

        $positions = $election->positions()
            ->with('candidates')
            ->get();

        return view('voter.vote', compact('election', 'positions'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'votes'       => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->votes as $positionId => $candidateId) {
                Vote::create([
                    'user_id'      => auth()->id(),
                    'election_id'  => $request->election_id,
                    'position_id'  => $positionId,
                    'candidate_id' => $candidateId,
                ]);
            }
        });

        return redirect()
            ->route('voter.dashboard')
            ->with('success', 'Your vote has been submitted successfully.');
    }
}

