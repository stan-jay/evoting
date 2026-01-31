@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">All Candidates</h1>
        <p class="text-gray-600">View all candidates in the system.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Name</th>
                    <th>Position</th>
                    <th>Election</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $candidate)
                    <tr class="border-b">
                        <td class="py-2">{{ $candidate->name }}</td>
                        <td>{{ $candidate->position->name }}</td>
                        <td>{{ $candidate->position->election->title }}</td>
                        <td>{{ ucfirst($candidate->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">
                            No candidates found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
