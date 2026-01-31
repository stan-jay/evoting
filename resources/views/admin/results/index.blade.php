@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Election Results</h1>
        <p class="text-gray-600">View results for completed elections.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Election</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($elections as $election)
                    <tr class="border-b">
                        <td class="py-2">{{ $election->title }}</td>
                        <td>{{ ucfirst($election->status) }}</td>
                        <td class="text-right">
                            @if($election->status === 'closed')
                            <a href="{{ route('admin.results.show', $election) }}" class="text-blue-600">
                                View Results
                            </a>
                            @else
                            <span class="text-gray-400 text-sm italic">
                                Results not available
                            </span>
                            @endif

                        </td>
                    </tr>
                @empty
                <tr>
                    @if($resultsVisible)
    <a href="{{ route('admin.results.pdf') }}"
       class="btn btn-danger">
        Download Official PDF
    </a>
@else
    <button class="btn btn-secondary" disabled>
        PDF Disabled (Results Not Published)
    </button>
@endif

                </tr>
                    <tr>
                        <td colspan="3" class="py-6 text-center text-gray-500">
                            No elections found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
