@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <p class="text-gray-600">System overview and election control.</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Users</p>
            <p class="text-2xl font-bold">{{ $users }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Elections</p>
            <p class="text-2xl font-bold">{{ $elections }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Active Elections</p>
            <p class="text-2xl font-bold text-blue-600">{{ $activeElections }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Total Votes</p>
            <p class="text-2xl font-bold text-green-600">{{ $totalVotes }}</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.elections.index') }}"
           class="bg-red-600 hover:bg-red-700 text-white p-6 rounded-xl font-semibold text-center">
            Manage Elections
        </a>

        <a href="{{ route('admin.candidates.index') }}"
           class="bg-blue-600 hover:bg-indigo-700 text-white p-6 rounded-xl font-semibold text-center">
            View Candidates
        </a>

        <a href="{{ route('admin.candidates.approval') }}"
           class="bg-yellow-600 hover:bg-yellow-700 text-white p-6 rounded-xl font-semibold text-center">
            Candidate Approval
        </a>
        <a href="{{ route('admin.results.index') }}"
           class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-xl font-semibold text-center">
            View Results
        </a>
    </div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <a href="{{ route('admin.audit.logs') }}"
       class="bg-gray-600 hover:bg-gray-700 text-white p-6 rounded-xl font-semibold text-center">
        Audit Log
        <p class="text-sm font-normal mt-1 opacity-80">
            Immutable voting records
        </p>
    </a>
</div>

</div>
@endsection
