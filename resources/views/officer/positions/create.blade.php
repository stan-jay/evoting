@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Add Position</h1>
        <p class="text-sm text-gray-600 mt-1">Create a new position for an election.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="POST" action="{{ route('officer.positions.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="election_id" class="block text-sm font-medium text-gray-700 mb-1">Election</label>
                <select id="election_id" name="election_id" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Select Election</option>
                    @foreach($elections as $election)
                        <option value="{{ $election->id }}">{{ $election->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Position Name</label>
                <input
                    id="name"
                    name="name"
                    placeholder="e.g. President"
                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required
                >
            </div>

            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Create Position
            </button>
        </form>
    </div>
</div>
@endsection
