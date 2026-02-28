@extends('layouts.app')

@section('content')
<div class="page-stack max-w-5xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Voting Session</p>
        <h1 class="page-title">{{ $election->title }}</h1>
        <p class="page-subtitle">Review each office carefully. Select one candidate per position and submit once your ballot is complete.</p>
    </section>

    <div class="section-card">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="section-title">Ballot Progress</h2>
                <p class="section-subtitle"><span id="progress-count">0</span> of {{ $positions->count() }} positions selected.</p>
            </div>
            <div class="w-full max-w-sm">
                <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                    <div id="progress-bar" class="h-full rounded-full bg-blue-800" style="width: 0%;"></div>
                </div>
                <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-500">Selections are required for every position</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('voter.vote.submit') }}" class="space-y-6" id="ballot-form">
        @csrf
        <input type="hidden" name="election_id" value="{{ $election->id }}">

        @foreach($positions as $index => $position)
            <section class="ballot-position" data-position-group>
                <div class="flex flex-col gap-2 border-b border-slate-200 pb-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Position {{ $index + 1 }}</p>
                        <h2 class="section-title">{{ $position->name }}</h2>
                        <p class="section-subtitle">Review manifestos before confirming your selection.</p>
                    </div>
                    <span class="status-badge status-active">Selection Required</span>
                </div>

                <div class="candidate-grid mt-5">
                    @foreach($position->candidates as $candidate)
                        <label class="candidate-option">
                            <input type="radio" name="votes[{{ $position->id }}]" value="{{ $candidate->id }}" required>
                            <div class="flex items-start gap-4">
                                @if(!empty($candidate->photo))
                                    <img src="{{ asset('storage/'.$candidate->photo) }}" alt="{{ $candidate->name }}" class="h-16 w-16 rounded-xl object-cover border border-slate-200">
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">No Image</div>
                                @endif

                                <div class="flex-1 space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-base font-semibold text-slate-900">{{ $candidate->name }}</p>
                                            <p class="text-sm text-slate-500">Candidate for {{ $position->name }}</p>
                                        </div>
                                        <span class="inline-flex h-5 w-5 rounded-full border-2 border-slate-300 bg-white"></span>
                                    </div>
                                    <p class="text-sm text-slate-500">Structured candidate review is available before you submit your final ballot.</p>
                                    <div>
                                        <a href="{{ route('voter.vote.candidate.show', [$election, $candidate]) }}" class="btn-tertiary relative z-10 -ml-2">View Manifesto</a>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="sticky-submit-bar">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Ballot Confirmation</p>
                    <p class="text-sm text-slate-500">Verify each selected candidate before submitting. Submission records your vote immediately.</p>
                </div>
                <button class="btn-primary w-full md:w-auto">Submit Final Vote</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ballot-form');
    if (!form) {
        return;
    }

    const groups = Array.from(form.querySelectorAll('[data-position-group]'));
    const progressCount = document.getElementById('progress-count');
    const progressBar = document.getElementById('progress-bar');

    const updateProgress = () => {
        const selected = groups.filter(group => group.querySelector('input[type="radio"]:checked')).length;
        const total = groups.length;
        const percent = total > 0 ? Math.round((selected / total) * 100) : 0;

        if (progressCount) {
            progressCount.textContent = selected;
        }

        if (progressBar) {
            progressBar.style.width = percent + '%';
        }
    };

    form.addEventListener('change', updateProgress);
    updateProgress();
});
</script>
@endpush
