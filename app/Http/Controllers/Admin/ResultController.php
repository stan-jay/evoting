<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;

class ResultController extends Controller
{
    /**
     * List all elections (admin)
     */
    public function index()
    {
        $elections = Election::latest()->get();
        return view('admin.results.index', compact('elections'));
    }

    /**
     * Show results for a specific election (ADMIN PREVIEW)
     */
    public function show(Election $election)
{
    if ($election->status !== 'closed' && $election->status !== 'declared') {
        return redirect()
            ->route('admin.results.index')
            ->with('error', 'Results are only available after the election is closed.');
    }

    $votes = Vote::where('election_id', $election->id)
        ->with('candidate.position')
        ->get();

    $resultsByPosition = [];

    $groupedByPosition = $votes->groupBy(
        fn ($vote) => $vote->candidate->position->id
    );

    foreach ($groupedByPosition as $positionVotes) {

        $ranked = $positionVotes
            ->groupBy('candidate_id')
            ->map(function ($items) {
                return [
                    'candidate' => $items->first()->candidate,
                    'votes'     => $items->count(),
                ];
            })
            ->sortByDesc('votes')
            ->values();

        if ($ranked->isEmpty()) {
            continue;
        }

        $resultsByPosition[] = [
            'position' => $ranked->first()['candidate']->position,
            'results'  => $ranked,
            'winner'   => $ranked->first(),
        ];
    }

    return view('admin.results.show', compact(
        'election',
        'resultsByPosition'
    ));
}



    /**
     * Export results to PDF (ONLY AFTER CLOSED)
     */
    public function pdf(Election $election)
    {
        if ($election->status !== 'closed') {
            return redirect()
                ->back()
                ->with('error', 'You can only export results after the election is closed.');
        }

        $votes = Vote::where('election_id', $election->id)
            ->with(['candidate.position'])
            ->get()
            ->groupBy('candidate.position_id');

        $resultsByPosition = [];

        foreach ($votes as $positionVotes) {
            $grouped = $positionVotes
                ->groupBy('candidate_id')
                ->map(function ($items) {
                    return [
                        'candidate' => $items->first()->candidate,
                        'votes'     => $items->count(),
                    ];
                })
                ->sortByDesc('votes')
                ->values();

            $resultsByPosition[] = [
                'position' => $grouped->first()['candidate']->position,
                'results'  => $grouped,
                'winner'   => $grouped->first(),
            ];
        }

        $pdf = app('dompdf.wrapper')
            ->loadView('admin.results.pdf', compact(
                'election',
                'resultsByPosition'
            ));

        return $pdf->download('election-results-' . $election->id . '.pdf');
    }
}
