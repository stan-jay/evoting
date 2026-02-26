@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-2xl font-bold">Published Results</h1>
        <p class="text-gray-600">View election results that have been published.</p>
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
                    <a href="{{ route('voter.results.show', $election) }}" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition">View Results</a>
                </div>
            @empty
                <p class="text-gray-500">No published results available yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
