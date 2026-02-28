@extends('layouts.app')

@section('content')
<div class="page-stack max-w-5xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Published Records</p>
        <h1 class="page-title">Election Results</h1>
        <p class="page-subtitle">Only formally declared elections appear in this archive.</p>
    </section>

    <section class="section-card">
        <h2 class="section-title">Declared Elections</h2>
        <p class="section-subtitle">Select an election to review the officially published result sheet.</p>

        <div class="mt-5 space-y-3">
            @forelse($elections as $election)
                <div class="data-row flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-2">
                        <p class="font-semibold text-slate-900">{{ $election->title }}</p>
                        <x-status-badge :status="$election->status" />
                    </div>
                    <a href="{{ route('voter.results.show', $election) }}" class="btn-secondary">View Results</a>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center text-sm text-slate-500">
                    No published results are available yet.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
