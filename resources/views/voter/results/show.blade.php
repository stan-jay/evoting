@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @include('components.breadcrumbs', [
        'items' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Dashboard', 'url' => Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard')],
            ['label' => $election->title, 'url' => Route::has('elections.show') ? route('elections.show', $election) : url('/elections/'.$election->id)],
            ['label' => 'Results'],
        ]
    ])

    <div class="bg-white shadow sm:rounded-lg p-6">
        <h1 class="text-3xl font-semibold text-gray-800">{{ $election->title }} - Published Results</h1>
        <p class="mt-2 text-sm text-gray-600">These results were officially published by the election admin.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <p class="text-sm text-gray-500">Total Votes</p>
            <p class="text-2xl font-bold">{{ $summary['total_votes'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <p class="text-sm text-gray-500">Unique Voters</p>
            <p class="text-2xl font-bold">{{ $summary['unique_voters'] }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Voting Trend (Hourly)</h2>
        <div class="h-72">
            <canvas id="trend-chart"></canvas>
        </div>
    </div>

    <div class="space-y-6">
        @if(isset($results) && $results->isNotEmpty())
            @foreach($results as $positionName => $candidates)
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $positionName }}</h2>
                    <div class="space-y-3">
                        @foreach($candidates as $candidate)
                            <div class="flex items-center justify-between border-b pb-3 last:border-b-0">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">{{ $candidate['name'] }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-blue-600">{{ $candidate['votes'] ?? 0 }}</p>
                                    <p class="text-sm text-gray-600">{{ $candidate['percentage'] ?? '0' }}%</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white shadow sm:rounded-lg p-6 text-center">
                <p class="text-gray-600">Results not yet available.</p>
            </div>
        @endif
    </div>

    <div class="flex flex-wrap gap-3 justify-between">
        <a href="{{ Route::has('elections.show') ? route('elections.show', $election) : url('/elections/'.$election->id) }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
            &larr; Back to Election
        </a>
        <a href="{{ Route::has('voter.dashboard') ? route('voter.dashboard') : url('/dashboard') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
            &larr; Back to Dashboard
        </a>
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
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                tension: 0.3,
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
