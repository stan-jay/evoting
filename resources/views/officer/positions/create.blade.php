@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Position</h2>

    <form method="POST" action="{{ route('officer.positions.store') }}">
        @csrf

        <select name="election_id" class="form-control mb-2" required>
            <option value="">Select Election</option>
            @foreach($elections as $election)
                <option value="{{ $election->id }}">
                    {{ $election->title }}
                </option>
            @endforeach
        </select>

        <input class="form-control mb-3"
               name="name"
               placeholder="Position Name (e.g. President)"
               required>

        <button class="btn btn-success">Create Position</button>
    </form>
</div>
@endsection
