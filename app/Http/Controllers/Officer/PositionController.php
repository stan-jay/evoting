<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Election;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('election')->get();
        $elections = Election::where('status', '!=', 'closed')->get();

        return view('officer.positions.index', compact('positions', 'elections'));
    }

    public function create()
    {
        $elections = Election::where('status', '!=', 'closed')->get();

        return view('officer.positions.create', compact('elections'));
    }

    public function show(Position $position)
    {
        return redirect()->route('officer.positions.edit', $position);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'election_id' => 'required|exists:elections,id',
        ]);

        Position::create([
            'name' => $request->name,
            'election_id' => $request->election_id,
        ]);

        return back()->with('success', 'Position added successfully.');
    }

    public function edit(Position $position)
    {
        return view('officer.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $position->update(['name' => $request->name]);

        return redirect()->route('officer.positions.index')
            ->with('success', 'Position updated.');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return back()->with('success', 'Position deleted.');
    }
}
