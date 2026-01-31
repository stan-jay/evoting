@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold">
        Vote — {{ $election->title }}
    </h1>

    <form method="POST" action="{{ route('voter.vote.submit') }}">
        @csrf
        <input type="hidden" name="election_id" value="{{ $election->id }}">

        @foreach($positions as $position)
            <div class="bg-white p-6 rounded shadow space-y-4">

                <h2 class="text-xl font-semibold">{{ $position->name }}</h2>

                @foreach($position->candidates as $candidate)
                    <label class="block border p-4 rounded cursor-pointer hover:border-blue-600">
                        <input type="radio"
                               name="votes[{{ $position->id }}]"
                               value="{{ $candidate->id }}"
                               required>
                        <span class="ml-2 font-medium">
                            {{ $candidate->name }}
                        </span>
                    </label>
                @endforeach

            </div>
        @endforeach

        <button class="bg-blue-600 text-white px-6 py-3 rounded font-semibold">
            Submit Vote
        </button>
    </form>
</div>
@endsection
