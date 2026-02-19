@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-2xl font-bold">Voter Dashboard</h1>
        <p class="text-gray-600">Choose an election to vote in or view results once published.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Elections</h2>

        <div class="space-y-3">
            @forelse($elections as $election)
                <div class="flex items-center justify-between border rounded-lg px-4 py-3">
                    <div>
                        <p class="font-semibold">{{ $election->title }}</p>
                        <p class="text-sm text-gray-500">Status: {{ ucfirst($election->status) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($election->status === 'active')
                            <a href="{{ route('voter.vote.create', $election) }}" class="px-4 py-2 rounded bg-blue-600 text-white">Vote</a>
                        @endif

                        @if($election->status === 'declared')
                            <a href="{{ route('voter.results.show', $election) }}" class="px-4 py-2 rounded bg-green-600 text-white">Results</a>
                        @elseif($election->status === 'closed')
                            <span class="px-4 py-2 rounded bg-gray-200 text-gray-700 text-sm">Awaiting publication</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No elections available right now.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
