@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <h1 class="text-2xl font-bold">User Management</h1>
        <p class="text-gray-600">Update role and status for registered users.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                placeholder="Search by name or email"
                class="w-full md:w-96 border rounded px-3 py-2"
            >
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b">
                            <td class="py-2">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="capitalize">{{ $user->role }}</td>
                            <td class="capitalize">{{ $user->status }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="border rounded px-2 py-1">
                                            <option value="voter" @selected($user->role === 'voter')>voter</option>
                                            <option value="officer" @selected($user->role === 'officer')>officer</option>
                                            <option value="admin" @selected($user->role === 'admin')>admin</option>
                                        </select>
                                        <select name="status" class="border rounded px-2 py-1">
                                            <option value="active" @selected($user->status === 'active')>active</option>
                                            <option value="suspended" @selected($user->status === 'suspended')>suspended</option>
                                        </select>
                                        <button type="submit" class="px-3 py-1 rounded bg-blue-600 text-white">Save</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white">Suspend</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
