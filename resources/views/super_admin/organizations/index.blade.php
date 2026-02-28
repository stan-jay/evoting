@extends('layouts.app')

@section('content')
<div class="page-stack max-w-6xl mx-auto">
    <section class="page-hero">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Governance Administration</p>
        <h1 class="page-title">Super Admin - Organizations</h1>
        <p class="page-subtitle">Onboard institutions, assign administrative ownership, and maintain oversight from a single governance panel.</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('super_admin.users.index') }}" class="btn-secondary">Intervene Users</a>
            <a href="{{ route('super_admin.docs.show') }}" class="btn-primary">View Docs</a>
        </div>
    </section>

    <section class="section-card">
        <h2 class="section-title">Create Organization</h2>
        <p class="section-subtitle">Provision a new institution and send an administrative onboarding invite.</p>

        <form method="POST" action="{{ route('super_admin.organizations.store') }}" class="mt-5 grid gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label class="form-label" for="name">Organization Name</label>
                <input id="name" name="name" value="{{ old('name') }}" class="form-input" required>
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="slug">Slug (optional)</label>
                <input id="slug" name="slug" value="{{ old('slug') }}" class="form-input">
                @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="admin_email">Admin Email</label>
                <input id="admin_email" type="email" name="admin_email" value="{{ old('admin_email') }}" class="form-input" required>
                @error('admin_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="admin_name">Admin Name (optional)</label>
                <input id="admin_name" name="admin_name" value="{{ old('admin_name') }}" class="form-input">
                @error('admin_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <button class="btn-primary">Create Organization & Admin Invite</button>
            </div>
        </form>
    </section>

    <section class="section-card">
        <h2 class="section-title">Organizations</h2>
        <p class="section-subtitle">Current institutional tenants and their platform footprint.</p>

        <div class="mt-5 overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr class="text-left">
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Users</th>
                        <th>Elections</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $organization)
                        <tr class="border-b border-slate-100">
                            <td class="py-4 font-semibold text-slate-900">{{ $organization->name }}</td>
                            <td>{{ $organization->slug }}</td>
                            <td>{{ $organization->users_count }}</td>
                            <td>{{ $organization->elections_count }}</td>
                            <td><x-status-badge :status="$organization->status" /></td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('super_admin.organizations.destroy', $organization) }}" onsubmit="return confirm('Delete this organization and all associated records?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-slate-500">No organizations yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $organizations->links() }}
        </div>
    </section>
</div>
@endsection
