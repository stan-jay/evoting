<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'manifesto' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $this->storePublicCandidatePhoto($request->file('photo'));
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
            'name' => 'required|string|max:255',
            'manifesto' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = [
            'name' => $request->name,
            'manifesto' => $request->manifesto,
        ];

        if ($request->hasFile('photo')) {
            $newPath = $this->storePublicCandidatePhoto($request->file('photo'));
            $this->deletePublicCandidatePhoto($candidate->photo);
            $data['photo'] = $newPath;
        }

        $candidate->update($data);

        return redirect()->route('officer.candidates.index')
            ->with('success', 'Candidate updated.');
    }

    public function destroy(Candidate $candidate)
    {
        $this->deletePublicCandidatePhoto($candidate->photo);
        $candidate->delete();

        return back()->with('success', 'Candidate deleted.');
    }

    private function storePublicCandidatePhoto(UploadedFile $file): string
    {
        $directory = public_path('uploads/candidates');
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/candidates/' . $filename;
    }

    private function deletePublicCandidatePhoto(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'uploads/')) {
            return;
        }

        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}
