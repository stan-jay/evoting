<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;

class CandidateApprovalController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('position.election')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.candidates.approval', compact('candidates'));
    }

    public function approve(Candidate $candidate)
    {
        $candidate->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Candidate approved successfully.');
    }

    public function reject(Candidate $candidate)
    {
        $candidate->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Candidate rejected.');
    }
}
