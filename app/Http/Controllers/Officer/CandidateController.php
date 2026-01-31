<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('position.election')->get();
        $positions  = Position::with('election')->get();

        return view('officer.candidates.index', compact('candidates', 'positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'position_id' => 'required|exists:positions,id',
        ]);

        Candidate::create([
            'name' => $request->name,
            'position_id' => $request->position_id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Candidate submitted for approval.');
    }

    public function edit(Candidate $candidate)
    {
        return view('officer.candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $candidate->update(['name' => $request->name]);

        return redirect()->route('officer.candidates.index')
            ->with('success', 'Candidate updated.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return back()->with('success', 'Candidate deleted.');
    }
}
