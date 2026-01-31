<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Vote;

if (!in_array($election->status, ['closed', 'declared'])) {
    abort(403, 'Results are not available yet.');
}


class ResultController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        $elections = Election::latest()->get();
        return view('admin.results.index', compact('elections'));
    }

    public function adminShow(Election $election)
    {
        if ($election->status === 'pending') {
            return redirect()
                ->route('admin.results.index')
                ->with('error', 'Election has not started.');
        }

        $resultsByPosition = $this->buildResults($election);

        return view('admin.results.show', compact(
            'election',
            'resultsByPosition'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VOTER & OFFICER (VIEW ONLY)
    |--------------------------------------------------------------------------
    */

    public function voterShow(Election $election)
    {
        if ($election->status !== 'closed') {
            abort(403, 'Results are not yet available.');
        }

        $resultsByPosition = $this->buildResults($election);

        return view('voter.results.show', compact(
            'election',
            'resultsByPosition'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SHARED RESULT BUILDER
    |--------------------------------------------------------------------------
    */

    private function buildResults(Election $election)
    {
        $votes = Vote::where('election_id', $election->id)
            ->with('candidate.position')
            ->get();

        $results = [];

        foreach ($votes->groupBy('candidate.position_id') as $group) {

            $position = $group->first()->candidate->position;
            $total = $group->count();

            $ranked = $group
                ->groupBy('candidate_id')
                ->map(function ($items) use ($total) {
                    return [
                        'candidate' => $items->first()->candidate,
                        'votes'     => $items->count(),
                        'percent'   => round(($items->count() / $total) * 100, 2),
                    ];
                })
                ->sortByDesc('votes')
                ->values();

            $results[] = [
                'position' => $position,
                'total'    => $total,
                'ranked'   => $ranked,
                'winner'   => $ranked->first(),
            ];
        }

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | PDF (ADMIN ONLY)
    |--------------------------------------------------------------------------
    */

    public function pdf(Election $election)
    {
        if ($election->status !== 'closed') {
            abort(403);
        }

        $resultsByPosition = $this->buildResults($election);

        return app('dompdf.wrapper')
            ->loadView('admin.results.pdf', compact(
                'election',
                'resultsByPosition'
            ))
            ->download('election-results-' . $election->id . '.pdf');
    }


    public function exportPdf()
{
    // SAFETY CHECK
    if (!settings('results_visible')) {
        abort(403, 'Results not published yet.');
    }

    $results = Result::with(['position', 'candidate'])
        ->orderBy('position_id')
        ->get();

    $data = [
        'results' => $results,
        'generatedAt' => now(),
        'electionName' => config('app.name'),
    ];

    $pdf = Pdf::loadView('admin.results.pdf', $data)
              ->setPaper('A4', 'portrait');

    return $pdf->download('official-election-results.pdf');
}
}
