@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Create Election</h1>
        <p class="text-gray-600">Set up a new election.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="POST" action="{{ route('admin.elections.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-medium">Election Title</label>
                <input name="title" class="border w-full px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Start Time</label>
                <input type="datetime-local" name="start_time"
                       class="border w-full px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">End Time</label>
                <input type="datetime-local" name="end_time"
                       class="border w-full px-3 py-2 rounded" required>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Create Election
            </button>
        </form>
    </div>

</div>
@endsection
