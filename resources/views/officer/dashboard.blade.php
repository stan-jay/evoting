@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold">Election Officer Dashboard</h1>
        <p class="text-gray-600">Manage positions and candidates.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded shadow">
            <h3 class="text-sm text-gray-500">Total Elections</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Election::count() }}</p>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <h3 class="text-sm text-gray-500">Positions</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Position::count() }}</p>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <h3 class="text-sm text-gray-500">Candidates</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Candidate::count() }}</p>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('officer.positions.index') }}"
           class="bg-indigo-600 text-white px-5 py-3 rounded">
            Manage Positions
        </a>

        <a href="{{ route('officer.candidates.index') }}"
           class="bg-green-600 text-white px-5 py-3 rounded">
            Manage Candidates
        </a>
    </div>

</div>
@endsection
