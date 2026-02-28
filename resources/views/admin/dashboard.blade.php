@extends('layouts.app')

@section('content')
<div class="page-stack max-w-7xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Administrative Control</p>
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Monitor platform activity, manage elections, and oversee institutional election operations.</p>
    </section>

    <section class="kpi-grid">
        <div class="kpi-card">
            <span class="kpi-icon">US</span>
            <p class="kpi-label">Registered Users</p>
            <p class="kpi-value">{{ $users }}</p>
        </div>
        <div class="kpi-card">
            <span class="kpi-icon">EL</span>
            <p class="kpi-label">Total Elections</p>
            <p class="kpi-value">{{ $elections }}</p>
        </div>
        <div class="kpi-card">
            <span class="kpi-icon">AC</span>
            <p class="kpi-label">Active Elections</p>
            <p class="kpi-value text-blue-800">{{ $activeElections }}</p>
        </div>
        <div class="kpi-card">
            <span class="kpi-icon">VT</span>
            <p class="kpi-label">Total Votes Cast</p>
            <p class="kpi-value text-emerald-800">{{ $totalVotes }}</p>
        </div>
    </section>

    <section class="section-card">
        <h2 class="section-title">Operational Actions</h2>
        <p class="section-subtitle">Use the appropriate control area for each administrative function.</p>

        <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-5">
            <a href="{{ route('admin.elections.index') }}" class="btn-primary w-full">Manage Elections</a>
            <a href="{{ route('admin.candidates.index') }}" class="btn-secondary w-full">View Candidates</a>
            <a href="{{ route('admin.candidates.approval') }}" class="btn-secondary w-full">Candidate Approval</a>
            <a href="{{ route('admin.results.index') }}" class="btn-secondary w-full">Review Results</a>
            <a href="{{ route('admin.audit.logs') }}" class="btn-secondary w-full">Audit Log</a>
        </div>
    </section>
</div>
@endsection
