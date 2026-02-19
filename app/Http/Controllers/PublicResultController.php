<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Vote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PublicResultController extends Controller
{
    public function showVoter(Election $election): View
    {
        $this->ensurePublishedForVoters($election);

        $results = $this->buildPublicResults($election);
        $trend = $this->buildVoteTrend($election);
        $summary = $this->buildSummary($election);

        return view('voter.results.show', compact('election', 'results', 'trend', 'summary'));
    }

    public function showOfficer(Election $election): View
    {
        $this->ensureVisibleForOfficers($election);

        $results = $this->buildPublicResults($election);
        $trend = $this->buildVoteTrend($election);
        $summary = $this->buildSummary($election);

        return view('officer.results.show', compact('election', 'results', 'trend', 'summary'));
    }

    public function liveOfficer(Election $election): JsonResponse
    {
        $this->ensureVisibleForOfficers($election);

        return response()->json([
            'summary' => $this->buildSummary($election),
            'trend' => $this->buildVoteTrend($election),
        ]);
    }

    private function ensurePublishedForVoters(Election $election): void
    {
        if ($election->status !== 'declared') {
            abort(403, 'Results are available only after admin publishes them.');
        }
    }

    private function ensureVisibleForOfficers(Election $election): void
    {
        if (!in_array($election->status, ['active', 'closed', 'declared'], true)) {
            abort(403, 'Result analytics are not yet available.');
        }
    }

    private function buildPublicResults(Election $election): Collection
    {
        $rows = Vote::query()
            ->select([
                'positions.id as position_id',
                'positions.name as position_name',
                'candidates.id as candidate_id',
                'candidates.name as candidate_name',
                DB::raw('COUNT(votes.id) as vote_count'),
            ])
            ->join('positions', 'positions.id', '=', 'votes.position_id')
            ->join('candidates', 'candidates.id', '=', 'votes.candidate_id')
            ->where('votes.election_id', $election->id)
            ->groupBy('positions.id', 'positions.name', 'candidates.id', 'candidates.name')
            ->orderBy('positions.name')
            ->orderByDesc('vote_count')
            ->get();

        return $rows
            ->groupBy('position_name')
            ->map(function (Collection $positionRows) {
                $total = (int) $positionRows->sum('vote_count');

                return $positionRows->map(function ($row) use ($total) {
                    $count = (int) $row->vote_count;

                    return [
                        'name' => $row->candidate_name,
                        'votes' => $count,
                        'percentage' => $total > 0
                            ? number_format(($count / $total) * 100, 2)
                            : '0.00',
                    ];
                })->values();
            });
    }

    private function buildVoteTrend(Election $election): array
    {
        $driver = DB::getDriverName();
        $bucketExpression = match ($driver) {
            'pgsql' => "to_char(date_trunc('hour', votes.created_at), 'YYYY-MM-DD HH24:00:00')",
            'mysql' => "DATE_FORMAT(votes.created_at, '%Y-%m-%d %H:00:00')",
            'sqlite' => "strftime('%Y-%m-%d %H:00:00', votes.created_at)",
            default => "to_char(date_trunc('hour', votes.created_at), 'YYYY-MM-DD HH24:00:00')",
        };

        $rows = Vote::query()
            ->selectRaw($bucketExpression . ' as bucket, COUNT(votes.id) as vote_count')
            ->where('votes.election_id', $election->id)
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        return [
            'labels' => $rows->pluck('bucket')->values()->all(),
            'data' => $rows->pluck('vote_count')->map(fn ($v) => (int) $v)->values()->all(),
        ];
    }

    private function buildSummary(Election $election): array
    {
        $totalVotes = Vote::where('election_id', $election->id)->count();
        $uniqueVoters = Vote::where('election_id', $election->id)->distinct('user_id')->count('user_id');

        return [
            'total_votes' => $totalVotes,
            'unique_voters' => $uniqueVoters,
            'is_published' => $election->status === 'declared',
        ];
    }
}
