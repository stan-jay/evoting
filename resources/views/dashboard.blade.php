@extends('layouts.app')

@section('content')
<div class="page-stack max-w-6xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Platform Access</p>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Access role-specific modules, review your account profile, and continue work within the election platform.</p>
    </section>

    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <div class="section-card">
            <h2 class="section-title">Quick Access</h2>
            <p class="section-subtitle">Open the correct workspace for your assigned responsibilities.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary">Admin Dashboard</a>
                    @elseif(Auth::user()->role === 'officer')
                        <a href="{{ route('officer.dashboard') }}" class="btn-primary">Officer Dashboard</a>
                    @else
                        <a href="{{ route('voter.dashboard') }}" class="btn-primary">Voter Dashboard</a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="section-card">
            <h2 class="section-title">Profile</h2>
            @auth
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-800">Name:</span> {{ Auth::user()->name }}</p>
                    <p><span class="font-semibold text-slate-800">Email:</span> {{ Auth::user()->email }}</p>
                    <p><span class="font-semibold text-slate-800">Role:</span> <span class="capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</span></p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn-secondary mt-5">Edit Profile</a>
            @endauth
        </div>

        <div class="section-card">
            <h2 class="section-title">Guidance</h2>
            <p class="section-subtitle">Use documentation and support channels when you need operational clarification.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="#" class="btn-secondary">Documentation</a>
                <a href="#" class="btn-secondary">Support</a>
            </div>
        </div>
    </section>
</div>
@endsection
