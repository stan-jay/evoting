@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold text-slate-900">Election Results</h1>
        <p class="text-slate-600">Review available election analytics for your organization.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead>
                    <tr class="border-b text-left text-slate-700">
                        <th class="py-2">Election</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($elections as $election)
                        <tr class="border-b">
                            <td class="py-3 font-medium text-slate-900">{{ $election->title }}</td>
                            <td><x-status-badge :status="$election->status" /></td>
                            <td class="text-right">
                                <a href="{{ route('officer.results.show', $election) }}" class="text-blue-700 hover:text-blue-800">View Analytics</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-500">No result entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $elections->links() }}
        </div>
    </div>
</div>
@endsection
