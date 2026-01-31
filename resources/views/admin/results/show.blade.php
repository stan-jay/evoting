@extends('layouts.app')

@section('content')
<div class="space-y-10">

    <div class="bg-white p-6 rounded-xl shadow border">
        <h1 class="text-2xl font-bold">
            Election Results — {{ $election->title }}
        </h1>
        <p class="text-gray-600 mt-1">
            Official results by position
        </p>
    </div>

    @foreach($resultsByPosition as $index => $block)
        <div class="bg-white p-6 rounded-xl shadow border space-y-6">

            <!-- Position Title -->
            <h2 class="text-xl font-semibold">
                {{ $block['position']->name }}
            </h2>

            <!-- Winner Banner -->
            <div class="bg-green-50 border border-green-300 p-4 rounded">
                🏆 <strong>{{ $block['winner']['candidate']->name }}</strong>
                declared winner with
                <strong>{{ $block['winner']['votes'] }}</strong> votes
            </div>

            <!-- Chart -->
            <canvas id="chart-{{ $index }}" height="120"></canvas>

            <!-- Ranking Table -->
            <div class="overflow-x-auto">
                <table class="w-full border mt-4">
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
                                <td class="p-3 font-bold">
                                    {{ $rank + 1 }}
                                </td>
                                <td class="p-3">
                                    {{ $row['candidate']->name }}
                                    @if($rank === 0)
                                        <span class="text-green-600 font-bold ml-2">
                                            🏆
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 font-semibold">
                                    {{ $row['votes'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    @endforeach

</div>

{{-- Charts --}}
<script>
@foreach($resultsByPosition as $index => $block)
    new Chart(document.getElementById('chart-{{ $index }}'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($block['results']->pluck('candidate.name')) !!},
            datasets: [{
                label: 'Votes',
                data: {!! json_encode($block['results']->pluck('votes')) !!},
                backgroundColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
@endforeach
</script>
@endsection
