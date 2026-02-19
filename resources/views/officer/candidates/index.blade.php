@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Candidates</h1>
        <p class="text-gray-600">Manage candidates (pending admin approval).</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="POST" action="{{ route('officer.candidates.store') }}" class="flex flex-col md:flex-row gap-4">
            @csrf
            <select name="position_id" class="border px-3 py-2 rounded w-full md:w-auto" required>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}">
                        {{ $position->name }} - {{ $position->election->title }}
                    </option>
                @endforeach
            </select>

            <input name="name" class="border px-3 py-2 rounded w-full" placeholder="Candidate name" required>

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add Candidate
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px]">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Candidate</th>
                        <th>Position</th>
                        <th>Election</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                        <tr class="border-b">
                            <td class="py-2">{{ $candidate->name }}</td>
                            <td>{{ $candidate->position->name }}</td>
                            <td>{{ $candidate->position->election->title }}</td>
                            <td>
                                <span class="px-2 py-1 text-sm rounded {{ $candidate->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($candidate->status) }}
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('officer.candidates.edit', $candidate) }}" class="text-blue-600">Edit</a>
                                <form method="POST" action="{{ route('officer.candidates.destroy', $candidate) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600" onclick="return confirm('Delete candidate?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">No candidates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
