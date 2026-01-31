@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Candidate</h2>

    <form method="POST"
          action="{{ route('officer.candidates.update', $candidate) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input class="form-control mb-2"
               name="name"
               value="{{ $candidate->name }}"
               required>

        <textarea class="form-control mb-2"
                  name="manifesto">{{ $candidate->manifesto }}</textarea>

        <select name="status" class="form-control mb-2">
            <option value="pending"  @selected($candidate->status=='pending')>Pending</option>
            <option value="approved" @selected($candidate->status=='approved')>Approved</option>
        </select>

        <input type="file" name="photo" class="form-control mb-3">

        <button class="btn btn-primary">Update Candidate</button>
    </form>
</div>
@endsection
