@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-2xl font-bold">Super Admin - User Intervention</h1>
        <p class="text-gray-600">Intervene on organization admins and officers when needed.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="GET" action="{{ route('super_admin.users.index') }}" class="grid gap-4 md:grid-cols-3">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search name or email" class="border rounded px-3 py-2">
            <input type="number" name="organization_id" value="{{ $organizationId }}" placeholder="Organization ID" class="border rounded px-3 py-2">
            <button class="px-4 py-2 rounded bg-blue-600 text-white">Filter</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm overflow-x-auto">
        <table class="w-full min-w-[900px]">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">User</th>
                    <th>Email</th>
                    <th>Organization</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b">
                        <td class="py-2">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->organization->name ?? '-' }} ({{ $user->organization_id ?? '-' }})</td>
                        <td>
                            <form method="POST" action="{{ route('super_admin.users.update', $user) }}" class="flex items-center gap-2 justify-end">
                                @csrf
                                @method('PUT')
                                <select name="role" class="border rounded px-2 py-1">
                                    @foreach(['voter','officer','admin','super_admin'] as $role)
                                        <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                                    @endforeach
                                </select>
                        </td>
                        <td>
                                <select name="status" class="border rounded px-2 py-1">
                                    <option value="active" @selected($user->status === 'active')>Active</option>
                                    <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
                                </select>
                        </td>
                        <td class="text-right">
                                <button class="text-blue-700">Apply</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
