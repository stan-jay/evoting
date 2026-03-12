@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">All Candidates</h1>
        <p class="text-gray-600">View all candidates in the system.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[740px]">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Photo</th>
                        <th class="py-2">Name</th>
                        <th>Position</th>
                        <th>Election</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                        <tr class="border-b">
                            <td class="py-2">
                                @if(!empty($candidate->photo_url))
                                    <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->name }}" class="h-10 w-10 rounded object-cover border border-slate-200">
                                @else
                                    <div class="h-10 w-10 rounded bg-slate-100 border border-slate-200 text-[10px] text-slate-500 flex items-center justify-center">N/A</div>
                                @endif
                            </td>
                            <td class="py-2">{{ $candidate->name }}</td>
                            <td>{{ $candidate->position->name }}</td>
                            <td>{{ $candidate->position->election->title }}</td>
                            <td>{{ ucfirst($candidate->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">
                                No candidates found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $candidates->links() }}
        </div>
    </div>

</div>
@endsection
