<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminResultController extends Controller
{
    public function index(): View
    {
        $elections = Election::latest()->get();

        return view('admin.results.index', compact('elections'));
    }

    public function show(Election $election): View|\Illuminate\Http\RedirectResponse
    {
        if (!in_array($election->status, ['active', 'closed', 'declared'], true)) {
            return redirect()
                ->route('admin.results.index')
                ->with('error', 'Result analytics are available only for active/closed elections.');
        }

        $resultsByPosition = $this->buildResultsByPosition($election);
        $trend = $this->buildVoteTrend($election);
        $summary = $this->buildSummary($election);

        return view('admin.results.show', compact('election', 'resultsByPosition', 'trend', 'summary'));
    }

    public function live(Election $election): JsonResponse
    {
        if (!in_array($election->status, ['active', 'closed', 'declared'], true)) {
            return response()->json(['message' => 'Analytics unavailable for this election state.'], 422);
        }

        return response()->json($this->buildAnalyticsPayload($election));
    }

    public function pdf(Election $election): Response|\Illuminate\Http\RedirectResponse
    {
        if (!in_array($election->status, ['closed', 'declared'], true)) {
            return redirect()
                ->back()
                ->with('error', 'You can only export results after the election is closed.');
        }

        $resultsByPosition = $this->buildResultsByPosition($election);

        $pdf = app('dompdf.wrapper')->loadView('admin.results.pdf', compact(
            'election',
            'resultsByPosition'
        ));

        return $pdf->download('election-results-' . $election->id . '.pdf');
    }

    public function excel(Election $election): StreamedResponse|\Illuminate\Http\RedirectResponse
    {
        if (!in_array($election->status, ['closed', 'declared'], true)) {
            return redirect()
                ->back()
                ->with('error', 'You can only export results after the election is closed.');
        }

        $resultsByPosition = $this->buildResultsByPosition($election);

        $filename = 'election-results-' . $election->id . '.csv';

        return response()->streamDownload(function () use ($resultsByPosition) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Position', 'Rank', 'Candidate', 'Votes']);

            foreach ($resultsByPosition as $block) {
                $positionName = $block['position']->name ?? 'Unknown';

                foreach (($block['results'] ?? []) as $index => $row) {
                    $candidate = $row['candidate']['name'] ?? 'Unknown';
                    $votes = (int) ($row['votes'] ?? 0);
                    fputcsv($handle, [$positionName, $index + 1, $candidate, $votes]);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function publish(Election $election): \Illuminate\Http\RedirectResponse
    {
        if ($election->status !== 'closed') {
            return back()->with('error', 'Only closed elections can be published.');
        }

        $election->update(['status' => 'declared']);

        return back()->with('success', 'Results published. Voters can now view results.');
    }

    public function unpublish(Election $election): \Illuminate\Http\RedirectResponse
    {
        if ($election->status !== 'declared') {
            return back()->with('error', 'Only published elections can be moved back to closed.');
        }

        $election->update(['status' => 'closed']);

        return back()->with('success', 'Results unpublished. Voters can no longer view results.');
    }

    private function buildResultsByPosition(Election $election): array
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
            ->groupBy('position_id')
            ->map(function (Collection $positionRows) {
                $ranked = $positionRows->map(function ($row) {
                    return [
                        'candidate' => ['name' => $row->candidate_name],
                        'votes' => (int) $row->vote_count,
                    ];
                })->values();

                return [
                    'position' => (object) ['name' => $positionRows->first()->position_name],
                    'results' => $ranked,
                    'winner' => $ranked->first(),
                ];
            })
            ->values()
            ->all();
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
        $eligibleVoters = User::where('role', 'voter')->where('status', 'active')->count();
        $turnout = $eligibleVoters > 0 ? round(($uniqueVoters / $eligibleVoters) * 100, 2) : 0.0;

        return [
            'total_votes' => $totalVotes,
            'unique_voters' => $uniqueVoters,
            'eligible_voters' => $eligibleVoters,
            'turnout_percentage' => $turnout,
            'is_published' => $election->status === 'declared',
        ];
    }

    private function buildAnalyticsPayload(Election $election): array
    {
        return [
            'summary' => $this->buildSummary($election),
            'trend' => $this->buildVoteTrend($election),
            'results_by_position' => $this->buildResultsByPosition($election),
            'status' => $election->status,
        ];
    }
}
