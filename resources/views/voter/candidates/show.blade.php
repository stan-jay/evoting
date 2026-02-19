@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-2xl font-bold">{{ $candidate->name }}</h1>
        <p class="text-gray-600 mt-1">Position: {{ $candidate->position->name }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm space-y-4">
        <div class="flex items-start gap-4">
            @if(!empty($candidate->photo))
                <img src="{{ asset('storage/'.$candidate->photo) }}" alt="{{ $candidate->name }}" class="h-28 w-28 rounded object-cover border">
            @else
                <div class="h-28 w-28 rounded bg-gray-100 border flex items-center justify-center text-xs text-gray-500">No Image</div>
            @endif
            <div class="text-sm text-gray-600">
                <p>This page keeps ballot selection compact while allowing voters to review details before voting.</p>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-2">Manifesto</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $candidate->manifesto ?: 'No manifesto provided by candidate.' }}</p>
        </div>
    </div>

    <div>
        <a href="{{ route('voter.vote.create', $election) }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
            Back to Ballot
        </a>
    </div>
</div>
@endsection
