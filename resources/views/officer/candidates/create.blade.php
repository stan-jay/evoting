@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h1 class="text-xl font-bold">Add Candidate</h1>
        <p class="text-gray-600">
            Candidates will be pending until approved by an admin.
        </p>
    </div>

    <!-- Form -->
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <form method="POST" action="{{ route('officer.candidates.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Position</label>
                <select name="position_id" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Select Position</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Candidate Name</label>
                <input type="text" name="name" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Manifesto</label>
                <textarea name="manifesto" rows="4" class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Photo (optional)</label>
                <input type="file" name="photo">
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                Submit Candidate
            </button>
        </form>
    </div>

</div>
@endsection
