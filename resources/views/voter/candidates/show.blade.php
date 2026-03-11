@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="section-card">
        <h1 class="text-2xl font-bold">{{ $candidate->name }}</h1>
        <p class="text-gray-600 mt-1">Position: {{ $candidate->position->name }}</p>
    </div>

    <div class="section-card space-y-4">
        <div class="flex items-start gap-4">
            @if(!empty($candidate->photo_url))
                <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->name }}" class="h-28 w-28 rounded object-cover border">
            @else
                <div class="h-28 w-28 rounded bg-gray-100 border flex items-center justify-center text-xs text-gray-500">No Image</div>
            @endif
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-2">Manifesto</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $candidate->manifesto ?: 'No manifesto provided by candidate.' }}</p>
        </div>
    </div>

    <div>
        <a href="{{ route('voter.vote.create', $election) }}" class="btn-secondary">Back to Ballot</a>
    </div>
</div>
@endsection
