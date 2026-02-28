@extends('layouts.app')

@section('content')
<div class="page-stack max-w-5xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Voter Portal</p>
        <h1 class="page-title">Voter Dashboard</h1>
        <p class="page-subtitle">Review active elections, access your ballot, and consult officially published results.</p>
    </section>

    <section class="section-card">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="section-title">Election Directory</h2>
                <p class="section-subtitle">Each election follows a controlled lifecycle with clear status indicators.</p>
            </div>
            <div class="hidden md:block text-xs uppercase tracking-[0.18em] text-slate-500">{{ $elections->count() }} listed</div>
        </div>

        <div class="mt-5 space-y-3">
            @forelse($elections as $election)
                <div class="data-row flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-2">
                        <p class="text-base font-semibold text-slate-900">{{ $election->title }}</p>
                        <div class="flex items-center gap-3">
                            <x-status-badge :status="$election->status" />
                            <span class="text-sm text-slate-500">Election access is governed by this status.</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($election->status === 'active')
                            <a href="{{ route('voter.vote.create', $election) }}" class="btn-primary">Open Ballot</a>
                        @endif

                        @if($election->status === 'declared')
                            <a href="{{ route('voter.results.show', $election) }}" class="btn-secondary">View Results</a>
                        @elseif($election->status === 'closed')
                            <span class="status-badge status-closed">Awaiting publication</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center text-sm text-slate-500">
                    No elections are available right now.
                </div>
            @endforelse
        </div>
    </section>

    <section class="section-card">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="section-title">Voting Guidance & Security</h2>
                <p class="section-subtitle">Essential reminders to help you vote correctly and protect your ballot credentials.</p>
            </div>
            <span class="hidden md:inline-flex status-badge status-active">Read Before Voting</span>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article class="section-card p-5 md:p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Voting Rules</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-900">One Ballot, One Submission</h3>
                <p class="mt-2 text-sm text-slate-600">Review every position before you submit. Once your ballot is recorded, changes may no longer be allowed.</p>
                <a href="{{ route('voter.vote.index') }}" class="btn-tertiary mt-4 -ml-2">Review Election Access</a>
            </article>

            <article class="section-card p-5 md:p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Security</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-900">Protect Your Login Session</h3>
                <p class="mt-2 text-sm text-slate-600">Do not share your credentials, avoid public devices, and always log out after voting on a shared computer.</p>
                <a href="{{ route('profile.edit') }}" class="btn-tertiary mt-4 -ml-2">Review Account Details</a>
            </article>

            <article class="section-card p-5 md:p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Compliance</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-900">Institutional Election Standards</h3>
                <p class="mt-2 text-sm text-slate-600">Follow your institution’s published eligibility rules, deadlines, and code of conduct before participating.</p>
                <a href="{{ route('home') }}" class="btn-tertiary mt-4 -ml-2">Read Platform Notices</a>
            </article>
        </div>

        <div class="section-divider mt-6 grid gap-4 md:grid-cols-2">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Before You Submit</h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li>Confirm each office has a deliberate candidate selection.</li>
                    <li>Check official election status badges before attempting to vote.</li>
                    <li>Only use links presented inside the official platform interface.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-base font-semibold text-slate-900">Good Security Practice</h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li>Never forward invitation or registration links to another person.</li>
                    <li>Report unusual access or duplicate vote prompts to your election administrators.</li>
                    <li>Return later to the Results section only after an election is formally declared.</li>
                </ul>
            </div>
        </div>
    </section>
</div>
@endsection
