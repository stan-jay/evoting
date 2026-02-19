@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-xl font-bold">Candidate Details</h1>
        <p class="text-sm text-gray-600 mt-1">This page is read-only for admin review.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm space-y-4">
        <div>
            <p class="text-sm text-gray-500">Name</p>
            <p class="font-medium">{{ $candidate->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Manifesto</p>
            <p class="text-gray-700">{{ $candidate->manifesto ?: 'No manifesto provided.' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Status</p>
            <p class="font-medium capitalize">{{ $candidate->status }}</p>
        </div>
        <div>
            <a href="{{ route('admin.candidates.index') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Back to Candidates
            </a>
        </div>
    </div>
</div>
@endsection
