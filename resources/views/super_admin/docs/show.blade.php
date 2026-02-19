@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Super Admin Documentation</h1>
            <p class="text-gray-600">Operational guide and production checklist for this e-voting platform.</p>
        </div>
        <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">Back to Dashboard</a>
    </div>

    <article class="prose max-w-none bg-white p-6 rounded-xl border shadow-sm">
        {!! $html !!}
    </article>
</div>
@endsection
