@extends('layouts.app')

@section('content')
<div class="page-stack max-w-6xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Election Operations</p>
        <h1 class="page-title">Election Officer Dashboard</h1>
        <p class="page-subtitle">Coordinate position management and candidate administration within a structured workflow.</p>
    </section>

    <section class="kpi-grid md:grid-cols-3 xl:grid-cols-3">
        <div class="kpi-card">
            <span class="kpi-icon">EL</span>
            <p class="kpi-label">Total Elections</p>
            <p class="kpi-value">{{ \App\Models\Election::count() }}</p>
        </div>
        <div class="kpi-card">
            <span class="kpi-icon">PO</span>
            <p class="kpi-label">Configured Positions</p>
            <p class="kpi-value">{{ \App\Models\Position::count() }}</p>
        </div>
        <div class="kpi-card">
            <span class="kpi-icon">CA</span>
            <p class="kpi-label">Submitted Candidates</p>
            <p class="kpi-value">{{ \App\Models\Candidate::count() }}</p>
        </div>
    </section>

    <section class="section-card">
        <h2 class="section-title">Primary Actions</h2>
        <p class="section-subtitle">Manage structured election setup through the dedicated modules below.</p>

        <div class="mt-5 flex flex-col gap-3 md:flex-row">
            <a href="{{ route('officer.positions.index') }}" class="btn-primary">Manage Positions</a>
            <a href="{{ route('officer.candidates.index') }}" class="btn-secondary">Manage Candidates</a>
        </div>
    </section>
</div>
@endsection
