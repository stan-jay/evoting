@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Audit Log</h1>
        <p class="text-gray-600">
            Immutable voting activity log (anonymous & tamper-safe).
        </p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">User Hash</th>
                    <th>Election</th>
                    <th>Position</th>
                    <th>Voted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr class="border-b">
                        <td class="py-2 text-xs">{{ $log->user_hash }}</td>
                        <td>{{ $log->election_id }}</td>
                        <td>{{ $log->position_id }}</td>
                        <td>{{ $log->voted_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $logs->links() }}
    </div>

</div>
@endsection
