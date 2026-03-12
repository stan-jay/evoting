@extends('layouts.app')

@section('content')
<div class="page-stack max-w-7xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Administrative Control</p>
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Centralized institutional overview with direct access to every election module.</p>
    </section>

    <section class="kpi-grid md:grid-cols-2 xl:grid-cols-5">
        <a href="{{ route('admin.elections.index') }}" class="kpi-card block hover:border-blue-200 transition-colors">
            <span class="kpi-icon">EL</span>
            <p class="kpi-label">Total Elections</p>
            <p class="kpi-value">{{ $elections }}</p>
        </a>

        <a href="{{ route('admin.elections.index') }}" class="kpi-card block hover:border-blue-200 transition-colors">
            <span class="kpi-icon">AC</span>
            <p class="kpi-label">Active Elections</p>
            <p class="kpi-value text-blue-800">{{ $activeElections }}</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="kpi-card block hover:border-blue-200 transition-colors">
            <span class="kpi-icon">VT</span>
            <p class="kpi-label">Total Voters</p>
            <p class="kpi-value">{{ $totalVoters }}</p>
        </a>

        <a href="{{ route('admin.candidates.index') }}" class="kpi-card block hover:border-blue-200 transition-colors">
            <span class="kpi-icon">CA</span>
            <p class="kpi-label">Total Candidates</p>
            <p class="kpi-value">{{ $totalCandidates }}</p>
        </a>

        <a href="{{ route('admin.results.index') }}" class="kpi-card block hover:border-blue-200 transition-colors">
            <span class="kpi-icon">VO</span>
            <p class="kpi-label">Total Votes</p>
            <p class="kpi-value text-emerald-800">{{ $totalVotes }}</p>
        </a>
    </section>
</div>
@endsection
