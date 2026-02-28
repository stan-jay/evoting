@extends('layouts.app')

@section('content')
<div class="page-stack max-w-7xl mx-auto">
    <section class="page-hero flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Election Administration</p>
            <h1 class="page-title">Elections</h1>
            <p class="page-subtitle">Create, schedule, close, and remove election records within a controlled administrative workflow.</p>
        </div>
        <a href="{{ route('admin.elections.create') }}" class="btn-primary">Create Election</a>
    </section>

    <section class="section-card">
        <h2 class="section-title">Election Register</h2>
        <p class="section-subtitle">Delete is available for records you need to remove from the system. Use it carefully because it is irreversible.</p>

        <div class="mt-5 overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr class="text-left">
                        <th>Title</th>
                        <th>Status</th>
                        <th>Start</th>
                        <th>End</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($elections as $election)
                        <tr class="border-b border-slate-100">
                            <td class="py-4 font-semibold text-slate-900">{{ $election->title }}</td>
                            <td><x-status-badge :status="$election->status" /></td>
                            <td>{{ optional($election->start_time)->format('M d, Y h:i A') ?? '-' }}</td>
                            <td>{{ optional($election->end_time)->format('M d, Y h:i A') ?? '-' }}</td>
                            <td class="text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.elections.edit', $election) }}" class="btn-secondary">Edit</a>

                                    @if($election->status === 'pending')
                                        <form method="POST" action="{{ route('admin.elections.start', $election) }}">
                                            @csrf
                                            <button class="btn-primary">Start</button>
                                        </form>
                                    @endif

                                    @if($election->status === 'active')
                                        <form method="POST" action="{{ route('admin.elections.close', $election) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn-secondary">Close</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.elections.destroy', $election) }}" onsubmit="return confirm('Delete this election? This action is irreversible and removes linked ballot records.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-slate-500">No elections found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
