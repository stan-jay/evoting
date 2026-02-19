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

    public function create()
    {
        $positions = Position::with('election')->get();

        return view('officer.candidates.create', compact('positions'));
    }

    public function show(Candidate $candidate)
    {
        return redirect()->route('officer.candidates.edit', $candidate);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'position_id' => 'required|exists:positions,id',
            'manifesto' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidates', 'public');
        }

        Candidate::create([
            'name' => $request->name,
            'position_id' => $request->position_id,
            'manifesto' => $request->manifesto,
            'photo' => $photoPath,
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
            'manifesto' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'manifesto' => $request->manifesto,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('officer.candidates.index')
            ->with('success', 'Candidate updated.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return back()->with('success', 'Candidate deleted.');
    }
}
