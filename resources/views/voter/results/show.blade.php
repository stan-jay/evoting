@extends('layouts.app')

@section('content')
<div class="page-stack max-w-6xl mx-auto">
    @include('components.breadcrumbs', [
        'items' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Dashboard', 'url' => Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard')],
            ['label' => $election->title],
            ['label' => 'Results'],
        ]
    ])

    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Declared Outcome</p>
        <h1 class="page-title">{{ $election->title }} Results</h1>
        <p class="page-subtitle">This statement reflects the officially published election outcome.</p>
    </section>

    <section class="kpi-grid md:grid-cols-2 xl:grid-cols-2">
        <div class="kpi-card">
            <span class="kpi-icon">TV</span>
            <p class="kpi-label">Total Votes</p>
            <p class="kpi-value">{{ $summary['total_votes'] }}</p>
        </div>
        <div class="kpi-card">
            <span class="kpi-icon">UV</span>
            <p class="kpi-label">Unique Voters</p>
            <p class="kpi-value">{{ $summary['unique_voters'] }}</p>
        </div>
    </section>

    <section class="section-card">
        <h2 class="section-title">Voting Trend</h2>
        <p class="section-subtitle">Hourly ballot volume across the election window.</p>
        <div class="mt-5 h-72">
            <canvas id="trend-chart"></canvas>
        </div>
    </section>

    <section class="page-stack">
        @if(isset($results) && $results->isNotEmpty())
            @foreach($results as $positionName => $candidates)
                <div class="section-card">
                    <h2 class="section-title">{{ $positionName }}</h2>
                    <div class="mt-4 space-y-3">
                        @foreach($candidates as $candidate)
                            <div class="data-row flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ $candidate['name'] }}</h3>
                                    <p class="text-sm text-slate-500">Official tally for this office.</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-blue-800">{{ $candidate['votes'] ?? 0 }}</p>
                                    <p class="text-sm text-slate-500">{{ $candidate['percentage'] ?? '0' }}%</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="section-card text-center text-sm text-slate-500">
                Results are not available.
            </div>
        @endif
    </section>

    <div class="flex flex-wrap gap-3 justify-between">
        <a href="{{ route('voter.results.index') }}" class="btn-secondary">Back to Results</a>
        <a href="{{ Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard') }}" class="btn-secondary">Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        return;
    }

    const trendPayload = @json($trend);
    const trendCtx = document.getElementById('trend-chart');
    if (!trendCtx) {
        return;
    }

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendPayload.labels || [],
            datasets: [{
                label: 'Votes per hour',
                data: trendPayload.data || [],
                borderColor: '#1e3a8a',
                backgroundColor: 'rgba(30, 58, 138, 0.08)',
                tension: 0.25,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, precision: 0 } }
        }
    });
});
</script>
@endsection
