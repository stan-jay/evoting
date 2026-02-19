@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-2xl font-bold">Super Admin - Organizations</h1>
        <p class="text-gray-600">Onboard and manage institutions from one platform.</p>
        <div class="mt-4 flex gap-2">
            <a href="{{ route('super_admin.users.index') }}" class="px-4 py-2 rounded bg-slate-800 text-white">Intervene Users</a>
            <a href="{{ route('super_admin.docs.show') }}" class="px-4 py-2 rounded bg-blue-700 text-white">View Docs</a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Create Organization</h2>
        <form method="POST" action="{{ route('super_admin.organizations.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Organization Name</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Slug (optional)</label>
                <input name="slug" value="{{ old('slug') }}" class="mt-1 w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Admin Email</label>
                <input type="email" name="admin_email" value="{{ old('admin_email') }}" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Admin Name (optional)</label>
                <input name="admin_name" value="{{ old('admin_name') }}" class="mt-1 w-full border rounded px-3 py-2">
            </div>
            <div class="md:col-span-2">
                <button class="px-4 py-2 rounded bg-blue-600 text-white">Create Organization & Admin Invite</button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Organizations</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Name</th>
                    <th>Slug</th>
                    <th>Users</th>
                    <th>Elections</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizations as $organization)
                    <tr class="border-b">
                        <td class="py-2">{{ $organization->name }}</td>
                        <td>{{ $organization->slug }}</td>
                        <td>{{ $organization->users_count }}</td>
                        <td>{{ $organization->elections_count }}</td>
                        <td>{{ ucfirst($organization->status) }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('super_admin.organizations.destroy', $organization) }}" onsubmit="return confirm('Delete this organization and all associated records?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">No organizations yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $organizations->links() }}
        </div>
    </div>
</div>
@endsection
