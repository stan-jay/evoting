<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use App\Models\VoteAudit;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
        if ($election->status !== 'active') {
            return redirect()
                ->route('voter.dashboard')
                ->with('error', 'Voting is not active for this election.');
        }

        $votedPositionIds = Vote::query()
            ->where('election_id', $election->id)
            ->where('user_id', Auth::id())
            ->pluck('position_id')
            ->all();

        $positions = $election->positions()
            ->whereNotIn('id', $votedPositionIds)
            ->with(['candidates' => function ($query) {
                $query->where('status', 'approved')->orderBy('name');
            }])
            ->get();

        if ($positions->isEmpty()) {
            return redirect()
                ->route('voter.dashboard')
                ->with('success', 'You have completed all ballot positions for this election.');
        }

        return view('voter.vote', compact('election', 'positions'));
    }

    public function candidateProfile(Election $election, Candidate $candidate)
    {
        if ($election->status !== 'active') {
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
            'votes' => 'required|array|min:1',
            'votes.*' => 'required|integer|exists:candidates,id',
        ]);

        $electionId = (int) $request->input('election_id');

        try {
            DB::transaction(function () use ($request, $electionId) {
                $election = Election::query()
                    ->lockForUpdate()
                    ->findOrFail($electionId);

                if ($election->status !== 'active') {
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
                        ! array_key_exists($positionId, $allowedCandidatesByPosition)
                        || ! in_array($candidateId, $allowedCandidatesByPosition[$positionId], true)
                    ) {
                        throw ValidationException::withMessages([
                            "votes.{$positionId}" => 'Invalid candidate selected for this position.',
                        ]);
                    }

                    $alreadyVoted = Vote::query()
                        ->where('user_id', Auth::id())
                        ->where('election_id', $election->id)
                        ->where('position_id', $positionId)
                        ->exists();

                    if ($alreadyVoted) {
                        throw ValidationException::withMessages([
                            "votes.{$positionId}" => 'You have already voted for this position.',
                        ]);
                    }

                    Vote::create([
                        'user_id' => Auth::id(),
                        'election_id' => $election->id,
                        'position_id' => $positionId,
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

        $remaining = Position::query()
            ->where('election_id', $electionId)
            ->whereNotIn('id', function ($query) use ($electionId) {
                $query->select('position_id')
                    ->from('votes')
                    ->where('user_id', Auth::id())
                    ->where('election_id', $electionId);
            })
            ->count();

        if ($remaining > 0) {
            return redirect()
                ->route('voter.vote.create', $electionId)
                ->with('success', 'Vote saved. Continue with the remaining positions.');
        }

        return redirect()
            ->route('voter.dashboard')
            ->with('success', 'Your vote has been submitted successfully.');
    }
}
