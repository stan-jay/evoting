<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::query()
            ->with('position.election')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.candidates.index', compact('candidates'));
    }
}
