@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="bg-white p-6 rounded-xl shadow border flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Election Analytics - {{ $election->title }}</h1>
            <p class="text-gray-600 mt-1">Live trend, standings, and publication controls</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($election->status === 'closed')
                <form method="POST" action="{{ route('admin.results.publish', $election) }}">
                    @csrf
                    @method('PATCH')
                    <button class="px-4 py-2 rounded bg-emerald-600 text-white">Publish Results</button>
                </form>
            @elseif($election->status === 'declared')
                <form method="POST" action="{{ route('admin.results.unpublish', $election) }}">
                    @csrf
                    @method('PATCH')
                    <button class="px-4 py-2 rounded bg-amber-600 text-white">Unpublish Results</button>
                </form>
            @endif

            @if(in_array($election->status, ['closed', 'declared']))
                <a href="{{ route('admin.results.excel', $election) }}" class="px-4 py-2 rounded bg-slate-700 text-white">Export Excel</a>
                <a href="{{ route('admin.results.pdf', $election) }}" class="px-4 py-2 rounded bg-blue-700 text-white">Export PDF</a>
            @endif
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-sm text-gray-500">Total Votes</p>
            <p id="kpi-total-votes" class="text-2xl font-bold">{{ $summary['total_votes'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-sm text-gray-500">Unique Voters</p>
            <p id="kpi-unique-voters" class="text-2xl font-bold">{{ $summary['unique_voters'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-sm text-gray-500">Eligible Voters</p>
            <p id="kpi-eligible-voters" class="text-2xl font-bold">{{ $summary['eligible_voters'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-sm text-gray-500">Turnout</p>
            <p id="kpi-turnout" class="text-2xl font-bold">{{ number_format($summary['turnout_percentage'], 2) }}%</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white p-6 rounded-xl shadow border">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold">Voting Trend (Hourly)</h2>
                <p class="text-xs text-gray-500">Last updated: <span id="live-last-updated">-</span></p>
            </div>
            <div class="h-72">
                <canvas id="trend-chart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow border">
            <h2 class="text-lg font-semibold mb-4">Status</h2>
            <p class="text-sm text-gray-600">Current election status: <strong id="live-election-status">{{ ucfirst($election->status) }}</strong></p>
            <p class="text-sm text-gray-600 mt-2">Published for voters: <strong id="live-published-status">{{ $summary['is_published'] ? 'Yes' : 'No' }}</strong></p>
        </div>
    </div>

    @foreach($resultsByPosition as $index => $block)
        <div class="bg-white p-6 rounded-xl shadow border space-y-6">
            <h2 class="text-xl font-semibold">{{ $block['position']->name }}</h2>

            <div class="bg-green-50 border border-green-300 p-4 rounded">
                <strong>{{ $block['winner']['candidate']['name'] ?? '-' }}</strong>
                leading with
                <strong>{{ $block['winner']['votes'] ?? 0 }}</strong> votes
            </div>

            <div class="h-64">
                <canvas id="position-chart-{{ $index }}"></canvas>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border min-w-[480px]">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Rank</th>
                            <th class="p-3 text-left">Candidate</th>
                            <th class="p-3 text-left">Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($block['results'] as $rank => $row)
                            <tr class="border-t {{ $rank === 0 ? 'bg-green-50' : '' }}">
                                <td class="p-3 font-bold">{{ $rank + 1 }}</td>
                                <td class="p-3">{{ $row['candidate']['name'] ?? 'Unknown' }} {{ $rank === 0 ? '(Leader)' : '' }}</td>
                                <td class="p-3 font-semibold">{{ $row['votes'] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        return;
    }

    const trendPayload = @json($trend);
    const liveUrl = @json(route('admin.results.live', $election));
    const positionPayloads = @json(collect($resultsByPosition)->map(function ($block) {
        return [
            'labels' => collect($block['results'] ?? [])->pluck('candidate.name')->values()->all(),
            'data' => collect($block['results'] ?? [])->pluck('votes')->map(fn ($v) => (int) $v)->values()->all(),
        ];
    })->values());

    let trendChart = null;
    const positionCharts = [];

    function renderTrend(payload) {
        const trendCtx = document.getElementById('trend-chart');
        if (!trendCtx) return;

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
    }

    function renderPositionCharts(payloads) {
        payloads.forEach((payload, idx) => {
            const ctx = document.getElementById('position-chart-' + idx);
            if (!ctx) return;

            if (positionCharts[idx]) {
                positionCharts[idx].destroy();
            }

            positionCharts[idx] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: payload.labels,
                    datasets: [{
                        label: 'Votes',
                        data: payload.data,
                        backgroundColor: '#2563eb',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { autoSkip: false } },
                        y: { beginAtZero: true, precision: 0 }
                    }
                }
            });
        });
    }

    function updateSummary(summary, status) {
        const totalVotes = document.getElementById('kpi-total-votes');
        const uniqueVoters = document.getElementById('kpi-unique-voters');
        const eligibleVoters = document.getElementById('kpi-eligible-voters');
        const turnout = document.getElementById('kpi-turnout');
        const statusEl = document.getElementById('live-election-status');
        const publishedEl = document.getElementById('live-published-status');

        if (totalVotes) totalVotes.textContent = summary.total_votes ?? 0;
        if (uniqueVoters) uniqueVoters.textContent = summary.unique_voters ?? 0;
        if (eligibleVoters) eligibleVoters.textContent = summary.eligible_voters ?? 0;
        if (turnout) turnout.textContent = `${Number(summary.turnout_percentage ?? 0).toFixed(2)}%`;
        if (statusEl && status) statusEl.textContent = String(status).charAt(0).toUpperCase() + String(status).slice(1);
        if (publishedEl) publishedEl.textContent = summary.is_published ? 'Yes' : 'No';
    }

    function updateLastUpdated() {
        const marker = document.getElementById('live-last-updated');
        if (!marker) return;
        marker.textContent = new Date().toLocaleTimeString();
    }

    const trendCtx = document.getElementById('trend-chart');
    if (!trendCtx) return;

    renderTrend(trendPayload);
    renderPositionCharts(positionPayloads);
    updateLastUpdated();

    async function refreshLiveAnalytics() {
        try {
            const response = await fetch(liveUrl, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) return;

            const payload = await response.json();
            updateSummary(payload.summary || {}, payload.status || null);
            renderTrend(payload.trend || { labels: [], data: [] });

            const livePositions = (payload.results_by_position || []).map((block) => ({
                labels: (block.results || []).map((row) => row?.candidate?.name || 'Unknown'),
                data: (block.results || []).map((row) => Number(row?.votes || 0)),
            }));
            renderPositionCharts(livePositions);
            updateLastUpdated();
        } catch (error) {
            // Keep the current UI state if polling fails.
        }
    }

    setInterval(refreshLiveAnalytics, 15000);
});
</script>
@endsection
