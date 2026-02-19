@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold">Vote - {{ $election->title }}</h1>

    <form method="POST" action="{{ route('voter.vote.submit') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="election_id" value="{{ $election->id }}">

        @foreach($positions as $position)
            <div class="bg-white p-6 rounded shadow space-y-4">
                <h2 class="text-xl font-semibold">{{ $position->name }}</h2>

                @foreach($position->candidates as $candidate)
                    <label class="flex items-start gap-3 border p-4 rounded cursor-pointer hover:border-blue-600">
                        <input type="radio" name="votes[{{ $position->id }}]" value="{{ $candidate->id }}" required class="mt-2 text-blue-600 focus:ring-blue-500">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                @if(!empty($candidate->photo))
                                    <img src="{{ asset('storage/'.$candidate->photo) }}" alt="{{ $candidate->name }}" class="h-12 w-12 rounded object-cover border">
                                @else
                                    <div class="h-12 w-12 rounded bg-gray-100 border flex items-center justify-center text-xs text-gray-500">No Img</div>
                                @endif
                                <span class="font-medium">{{ $candidate->name }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Detailed profile and manifesto available via link below.</p>
                            <a href="{{ route('voter.vote.candidate.show', [$election, $candidate]) }}" class="text-sm text-blue-700 hover:underline">View Candidate Manifesto</a>
                        </div>
                    </label>
                @endforeach
            </div>
        @endforeach

        <button class="bg-blue-600 text-white px-6 py-3 rounded font-semibold hover:bg-blue-700">Submit Vote</button>
    </form>
</div>
@endsection
