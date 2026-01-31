@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Candidate Approval</h1>
        <p class="text-gray-600">
            Review and approve candidates submitted by officers.
        </p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <table class="w-full">
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
                        <td class="py-2 font-medium">{{ $candidate->name }}</td>
                        <td>{{ $candidate->position->name }}</td>
                        <td>{{ $candidate->position->election->title }}</td>
                        <td>
                            <span class="px-2 py-1 text-sm rounded bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        </td>
                        <td class="text-right space-x-2">
                            <form method="POST" action="{{ route('admin.candidates.approve', $candidate) }}" class="inline">
                                @csrf
                                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.candidates.reject', $candidate) }}" class="inline">
                                @csrf
                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
                                        onclick="return confirm('Reject this candidate?')">
                                    Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">
                            No pending candidates.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
