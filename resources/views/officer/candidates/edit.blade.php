@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Edit Candidate</h1>
        <p class="text-sm text-gray-600 mt-1">Update candidate profile details.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="POST" action="{{ route('officer.candidates.update', $candidate) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Candidate Name</label>
                <input
                    id="name"
                    name="name"
                    value="{{ $candidate->name }}"
                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required
                >
            </div>

            <div>
                <label for="manifesto" class="block text-sm font-medium text-gray-700 mb-1">Manifesto</label>
                <textarea
                    id="manifesto"
                    name="manifesto"
                    rows="4"
                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >{{ $candidate->manifesto }}</textarea>
            </div>

            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                <input id="photo" type="file" name="photo" class="block w-full text-sm text-gray-700">
            </div>

            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Update Candidate
            </button>
        </form>
    </div>
</div>
@endsection
