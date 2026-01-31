@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Election Positions</h1>
        <p class="text-gray-600">Manage positions for elections.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="POST" action="{{ route('officer.positions.store') }}" class="flex gap-4">
            @csrf
            <input name="name" class="border px-3 py-2 rounded w-full" placeholder="Position name" required>

            <select name="election_id" class="border px-3 py-2 rounded" required>
                @foreach($elections as $election)
                    <option value="{{ $election->id }}">{{ $election->title }}</option>
                @endforeach
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Add Position
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Position</th>
                    <th>Election</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $position)
                    <tr class="border-b">
                        <td class="py-2">{{ $position->name }}</td>
                        <td>{{ $position->election->title }}</td>
                        <td>{{ ucfirst($position->election->status) }}</td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('officer.positions.edit', $position) }}" class="text-blue-600">Edit</a>
                            <form method="POST" action="{{ route('officer.positions.destroy', $position) }}" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('Delete this position?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">
                            No positions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
