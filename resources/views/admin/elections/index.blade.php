@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold">Elections</h1>
            <p class="text-gray-600">Manage all elections.</p>
        </div>
        <a href="{{ route('admin.elections.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            Create Election
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Title</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>End</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($elections as $election)
                    <tr class="border-b">
                        <td class="py-2">{{ $election->title }}</td>
                        <td>{{ ucfirst($election->status) }}</td>
                        <td>{{ $election->start_time }}</td>
                        <td>{{ $election->end_time }}</td>
                        <td class="text-right space-x-2">
                          <td class="space-x-2">

    <a href="{{ route('admin.elections.edit', $election) }}"
       class="text-blue-600">Edit</a>

    @if($election->status === 'pending')
        <form method="POST"
              action="{{ route('admin.elections.start', $election) }}"
              class="inline">
            @csrf
            <button class="text-green-600 font-semibold">
                Start
            </button>
        </form>
    @endif

    @if($election->status === 'active')
        <form method="POST"
              action="{{ route('admin.elections.close', $election) }}"
              class="inline">
            @csrf
            <button class="text-red-600 font-semibold">
                Close
            </button>
        </form>
    @endif

</td>
  
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">
                            No elections found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
