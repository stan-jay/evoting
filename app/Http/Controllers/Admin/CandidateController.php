<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('position.election')
            ->latest()
            ->get();

        return view('admin.candidates.index', compact('candidates'));
    }
}
