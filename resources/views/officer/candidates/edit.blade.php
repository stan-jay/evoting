@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="section-card">
        <h1 class="text-xl font-bold">Edit Candidate</h1>
        <p class="text-sm text-gray-600 mt-1">Update candidate details and replace the profile image if needed.</p>
    </div>

    <div class="section-card">
        <form method="POST" action="{{ route('officer.candidates.update', $candidate) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="form-label">Candidate Name</label>
                <input id="name" name="name" value="{{ old('name', $candidate->name) }}" class="form-input" required>
            </div>

            <div>
                <label for="manifesto" class="form-label">Manifesto</label>
                <textarea id="manifesto" name="manifesto" rows="4" class="form-input">{{ old('manifesto', $candidate->manifesto) }}</textarea>
            </div>

            <div>
                <label for="photo" class="form-label">Candidate Photo</label>
                @if(!empty($candidate->photo_url))
                    <div class="mt-2 mb-3">
                        <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->name }}" class="h-24 w-24 rounded-lg object-cover border border-slate-200">
                    </div>
                @endif
                <input id="photo" type="file" name="photo" accept="image/*" class="block w-full text-sm text-gray-700">
                <p class="mt-1 text-xs text-gray-500">Upload JPG, PNG, or WEBP (max 4MB).</p>
            </div>

            <button type="submit" class="btn-primary">Update Candidate</button>
        </form>
    </div>
</div>
@endsection
