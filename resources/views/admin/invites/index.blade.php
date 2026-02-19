@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-2xl font-bold">Organization Invites</h1>
        <p class="text-gray-600">Invite users via secure links. Registration is invite-only.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Create Invites</h2>
        <form method="POST" action="{{ route('admin.invites.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Emails (comma, space, or new line separated)</label>
                <textarea name="emails" rows="5" class="mt-1 w-full border rounded px-3 py-2" required>{{ old('emails') }}</textarea>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="voter">Voter</option>
                        <option value="officer">Officer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expiry (days)</label>
                    <input type="number" name="expires_in_days" min="1" max="30" value="{{ old('expires_in_days', 7) }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>
            <button class="px-4 py-2 rounded bg-blue-600 text-white">Create Invite Links</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Recent Invites</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Email</th>
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
                    <tr class="border-b">
                        <td class="py-2">{{ $invite->email }}</td>
                        <td>{{ ucfirst($invite->role) }}</td>
                        <td>{{ optional($invite->invite_sent_at)->toDateTimeString() ?? 'Pending' }}</td>
                        <td>{{ optional($invite->expires_at)->toDateTimeString() ?? 'Never' }}</td>
                        <td>{{ $invite->accepted_at ? 'Accepted' : 'Pending' }}</td>
                        <td class="max-w-xs truncate text-xs text-red-600">{{ $invite->send_error ?: '-' }}</td>
                        <td class="max-w-xs truncate">
                            {{ route('register', ['token' => $invite->token]) }}
                        </td>
                        <td class="text-right">
                            @if(!$invite->accepted_at)
                                <form method="POST" action="{{ route('admin.invites.resend', $invite) }}">
                                    @csrf
                                    <button class="text-blue-600">Refresh</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-gray-500">No invites created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $invites->links() }}
        </div>
    </div>
</div>
@endsection
