<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::latest()->get();
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Election::create([
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election created.');
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $election->update($request->only('title', 'start_time', 'end_time'));

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election updated.');
    }

    public function destroy(Election $election)
    {
        $election->delete();

        return back()->with('success', 'Election deleted.');
    }

    public function activate(Election $election)
{
    if ($election->status !== 'pending') {
        return back()->with('error', 'Only pending elections can be activated.');
    }

    $election->update([
        'status' => 'active',
        'start_at' => now(),
    ]);

    return back()->with('success', 'Election started.');
}

public function start(Election $election)
{
    if ($election->status !== 'pending') {
        return back()->with('error', 'Election already started.');
    }

    $election->update([
        'status' => 'active',
        'start_at' => now(),
    ]);

    return back()->with('success', 'Election started successfully.');
}

public function close(Election $election)
{
    if ($election->status !== 'active') {
        return back()->with('error', 'Only active elections can be closed.');
    }

    $election->update([
        'status' => 'closed',
        'end_at' => now(),
    ]);

    return back()->with('success', 'Election closed and results locked.');
}


}
