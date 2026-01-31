@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">

    <div class="bg-white p-6 rounded-xl shadow border">
        <h1 class="text-2xl font-bold">
            Vote — {{ $election->title }}
        </h1>
        <p class="text-gray-600">
            Select one candidate per position
        </p>
    </div>

    <form method="POST" action="{{ route('voter.vote.submit') }}">
        @csrf
        <input type="hidden" name="election_id" value="{{ $election->id }}">

        @foreach($positions as $position)
            <div class="bg-white p-6 rounded-xl shadow border space-y-4">

                <h2 class="text-xl font-semibold">
                    {{ $position->name }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($position->candidates as $candidate)
                        <label class="border rounded-lg p-4 cursor-pointer hover:border-blue-600 transition">
                            <div class="flex items-start gap-3">
                                <input type="radio"
                                       name="votes[{{ $position->id }}]"
                                       value="{{ $candidate->id }}"
                                       class="mt-1">

                                <div>
                                    <p class="font-semibold text-lg">
                                        {{ $candidate->name }}
                                    </p>
                                    @if($candidate->bio)
                                        <p class="text-gray-600 text-sm mt-1">
                                            {{ $candidate->bio }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

            </div>
        @endforeach

        <div class="text-right">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-semibold">
                Submit Vote
            </button>
        </div>
    </form>

</div>
@endsection
