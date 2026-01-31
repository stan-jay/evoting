@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Position</h2>

    <form method="POST" action="{{ route('officer.positions.update', $position) }}">
        @csrf
        @method('PUT')

        <input class="form-control mb-3"
               name="name"
               value="{{ $position->name }}"
               required>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
