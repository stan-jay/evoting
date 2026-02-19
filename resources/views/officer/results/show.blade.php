@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h1 class="text-3xl font-semibold text-gray-800">{{ $election->title }} - Officer Analytics</h1>
        <p class="mt-2 text-sm text-gray-600">Live trend updates and per-position standings.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <p class="text-sm text-gray-500">Total Votes</p>
            <p id="kpi-total-votes" class="text-2xl font-bold">{{ $summary['total_votes'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <p class="text-sm text-gray-500">Unique Voters</p>
            <p id="kpi-unique-voters" class="text-2xl font-bold">{{ $summary['unique_voters'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <p class="text-sm text-gray-500">Published to Voters</p>
            <p id="kpi-published" class="text-2xl font-bold">{{ $summary['is_published'] ? 'Yes' : 'No' }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="text-lg font-semibold">Voting Trend (Hourly)</h2>
            <p class="text-xs text-gray-500">Last updated: <span id="live-last-updated">-</span></p>
        </div>
        <div class="h-72">
            <canvas id="trend-chart"></canvas>
        </div>
    </div>

    <div class="space-y-6">
        @if($results->isNotEmpty())
            @foreach($results as $positionName => $candidates)
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $positionName }}</h2>
                    <div class="space-y-3">
                        @foreach($candidates as $candidate)
                            <div class="flex items-center justify-between border-b pb-3 last:border-b-0">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $candidate['name'] }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-blue-600">{{ $candidate['votes'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $candidate['percentage'] }}%</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white shadow sm:rounded-lg p-6 text-center">
                <p class="text-gray-600">No votes recorded yet.</p>
            </div>
        @endif
    </div>

    <div>
        <a href="{{ route('officer.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
            Back to Dashboard
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
    const liveUrl = @json(route('officer.results.live', $election));
    const trendCtx = document.getElementById('trend-chart');
    if (!trendCtx) {
        return;
    }

    let trendChart = null;

    function renderTrend(payload) {
        if (trendChart) {
            trendChart.destroy();
        }

        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: payload.labels || [],
                datasets: [{
                    label: 'Votes per hour',
                    data: payload.data || [],
                    borderColor: '#0891b2',
                    backgroundColor: 'rgba(8, 145, 178, 0.15)',
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
    }

    function updateSummary(summary) {
        const totalVotes = document.getElementById('kpi-total-votes');
        const uniqueVoters = document.getElementById('kpi-unique-voters');
        const published = document.getElementById('kpi-published');

        if (totalVotes) totalVotes.textContent = summary.total_votes ?? 0;
        if (uniqueVoters) uniqueVoters.textContent = summary.unique_voters ?? 0;
        if (published) published.textContent = summary.is_published ? 'Yes' : 'No';
    }

    function updateLastUpdated() {
        const marker = document.getElementById('live-last-updated');
        if (!marker) return;
        marker.textContent = new Date().toLocaleTimeString();
    }

    renderTrend(trendPayload);
    updateLastUpdated();

    async function refreshLiveAnalytics() {
        try {
            const response = await fetch(liveUrl, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) return;

            const payload = await response.json();
            updateSummary(payload.summary || {});
            renderTrend(payload.trend || { labels: [], data: [] });
            updateLastUpdated();
        } catch (error) {
            // Keep the current UI state if polling fails.
        }
    }

    setInterval(refreshLiveAnalytics, 15000);
});
</script>
@endsection
