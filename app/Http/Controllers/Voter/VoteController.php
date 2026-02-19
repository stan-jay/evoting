<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use App\Models\VoteAudit;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoteController extends Controller
{
    /**
     * Show voter dashboard (list of elections).
     */
    public function index()
    {
        $elections = Election::orderBy('start_time', 'asc')->get();

        return view('voter.dashboard', compact('elections'));
    }

    /**
     * Show voting page for an election.
     */
    public function show(Election $election)
    {
        if ($election->status !== 'active' || now()->lt($election->start_time) || now()->gt($election->end_time)) {
            return redirect()
                ->route('voter.dashboard')
                ->with('error', 'Voting is not active for this election.');
        }

        $positions = $election->positions()
            ->with(['candidates' => function ($query) {
                $query->where('status', 'approved')->orderBy('name');
            }])
            ->get();

        return view('voter.vote', compact('election', 'positions'));
    }

    public function candidateProfile(Election $election, Candidate $candidate)
    {
        if ($election->status !== 'active' || now()->lt($election->start_time) || now()->gt($election->end_time)) {
            return redirect()
                ->route('voter.dashboard')
                ->with('error', 'Candidate details are unavailable outside active voting window.');
        }

        $candidate->load('position');

        if (
            $candidate->status !== 'approved'
            || ! $candidate->position
            || $candidate->position->election_id !== $election->id
        ) {
            abort(404);
        }

        return view('voter.candidates.show', compact('election', 'candidate'));
    }

    /**
     * Submit votes for an election.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'votes'       => 'required|array',
            'votes.*'     => 'required|integer|exists:candidates,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $election = Election::query()
                    ->lockForUpdate()
                    ->findOrFail((int) $request->input('election_id'));

                if (
                    $election->status !== 'active'
                    || now()->lt($election->start_time)
                    || now()->gt($election->end_time)
                ) {
                    throw ValidationException::withMessages([
                        'election_id' => 'This election is not accepting votes right now.',
                    ]);
                }

                $allowedCandidatesByPosition = Candidate::query()
                    ->select(['candidates.id as candidate_id', 'positions.id as position_id'])
                    ->join('positions', 'positions.id', '=', 'candidates.position_id')
                    ->where('positions.election_id', $election->id)
                    ->where('candidates.status', 'approved')
                    ->get()
                    ->groupBy('position_id')
                    ->map(fn ($rows) => $rows->pluck('candidate_id')->map(fn ($id) => (int) $id)->all())
                    ->all();

                foreach ($request->input('votes', []) as $positionId => $candidateId) {
                    $positionId = (int) $positionId;
                    $candidateId = (int) $candidateId;

                    if (
                        !array_key_exists($positionId, $allowedCandidatesByPosition)
                        || !in_array($candidateId, $allowedCandidatesByPosition[$positionId], true)
                    ) {
                        throw ValidationException::withMessages([
                            "votes.{$positionId}" => 'Invalid candidate selected for this position.',
                        ]);
                    }

                    Vote::create([
                        'user_id'      => Auth::id(),
                        'election_id'  => $election->id,
                        'position_id'  => $positionId,
                        'candidate_id' => $candidateId,
                    ]);

                    VoteAudit::create([
                        'user_hash' => hash('sha256', Auth::id() . '|' . $election->id . '|' . config('app.key')),
                        'election_id' => $election->id,
                        'position_id' => $positionId,
                        'voted_at' => now(),
                    ]);
                }
            });
        } catch (QueryException $e) {
            if (in_array((string) $e->getCode(), ['23505', '23000', '19'], true)) {
                throw ValidationException::withMessages([
                    'votes' => 'Duplicate vote detected. You can vote only once per position.',
                ]);
            }

            throw $e;
        }

        return redirect()
            ->route('voter.dashboard')
            ->with('success', 'Your vote has been submitted successfully.');
    }
}
