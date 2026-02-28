@extends('layouts.app')

@section('content')
<div class="page-stack max-w-6xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Administrative Onboarding</p>
        <h1 class="page-title">Organization Invites</h1>
        <p class="page-subtitle">Issue controlled registration links for voters, officers, and administrators.</p>
    </section>

    <section class="section-card">
        <h2 class="section-title">Create Invites</h2>
        <p class="section-subtitle">Provide one or more email addresses separated by commas, spaces, or line breaks.</p>

        <form method="POST" action="{{ route('admin.invites.store') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="form-label">Emails</label>
                <textarea name="emails" rows="5" class="form-input" required>{{ old('emails') }}</textarea>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="form-label">Role</label>
                    <select name="role" class="form-input">
                        <option value="voter">Voter</option>
                        <option value="officer">Officer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Expiry (days)</label>
                    <input type="number" name="expires_in_days" min="1" max="30" value="{{ old('expires_in_days', 7) }}" class="form-input">
                </div>
            </div>
            <button class="btn-primary">Create Invite Links</button>
        </form>
    </section>

    <section class="section-card">
        <h2 class="section-title">Recent Invites</h2>
        <p class="section-subtitle">Review delivery state, expiry, and resend eligibility.</p>

        <div class="mt-5 overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr class="text-left">
                        <th>Email</th>
                        <th>Role</th>
                        <th>Sent At</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Error</th>
                        <th>Link</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invites as $invite)
                        <tr class="border-b border-slate-100">
                            <td class="py-4 font-medium text-slate-900">{{ $invite->email }}</td>
                            <td>{{ ucfirst($invite->role) }}</td>
                            <td>{{ optional($invite->invite_sent_at)->toDateTimeString() ?? 'Pending' }}</td>
                            <td>{{ optional($invite->expires_at)->toDateTimeString() ?? 'Never' }}</td>
                            <td>
                                @if($invite->accepted_at)
                                    <span class="status-badge status-declared">Accepted</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                            <td class="max-w-xs truncate text-xs text-red-600">{{ $invite->send_error ?: '-' }}</td>
                            <td class="max-w-xs truncate text-xs text-slate-500">{{ route('register', ['token' => $invite->token]) }}</td>
                            <td class="text-right">
                                @if(!$invite->accepted_at)
                                    <form method="POST" action="{{ route('admin.invites.resend', $invite) }}">
                                        @csrf
                                        <button class="btn-secondary">Resend</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-sm text-slate-500">No invites created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $invites->links() }}
        </div>
    </section>
</div>
@endsection
