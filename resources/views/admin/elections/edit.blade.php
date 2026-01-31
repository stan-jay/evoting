@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Edit Election</h1>
        <p class="text-gray-600">Update election details.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="POST" action="{{ route('admin.elections.update', $election) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-medium">Election Title</label>
                <input name="title" value="{{ $election->title }}"
                       class="border w-full px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Start Time</label>
                <input type="datetime-local" name="start_time"
                       value="{{ $election->start_time->format('Y-m-d\TH:i') }}"
                       class="border w-full px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">End Time</label>
                <input type="datetime-local" name="end_time"
                       value="{{ $election->end_time->format('Y-m-d\TH:i') }}"
                       class="border w-full px-3 py-2 rounded" required>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Update Election
            </button>
        </form>
    </div>

</div>
@endsection
