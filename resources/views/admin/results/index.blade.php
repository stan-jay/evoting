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
                            <div class="inline-flex items-center gap-3">
                                @if(in_array($election->status, ['active', 'closed', 'declared']))
                                    <a href="{{ route('admin.results.show', $election) }}" class="text-blue-600">
                                        View Analytics
                                    </a>
                                @endif

                                @if($election->status === 'closed')
                                    <form method="POST" action="{{ route('admin.results.publish', $election) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-700">Publish</button>
                                    </form>
                                @elseif($election->status === 'declared')
                                    <form method="POST" action="{{ route('admin.results.unpublish', $election) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-amber-700">Unpublish</button>
                                    </form>
                                @endif
                            </div>

                        </td>
                    </tr>
                @empty
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
