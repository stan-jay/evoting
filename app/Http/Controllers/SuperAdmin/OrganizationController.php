<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrganizationInviteJob;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(): View
    {
        $organizations = Organization::query()
            ->withCount(['users', 'elections'])
            ->latest()
            ->paginate(20);

        return view('super_admin.organizations.index', compact('organizations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:organizations,slug'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_name' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name']) . '-' . Str::lower(Str::random(5));

        $organization = Organization::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'status' => 'active',
        ]);

        $invite = OrganizationInvite::withoutGlobalScope('organization')->create([
            'organization_id' => $organization->id,
            'email' => $validated['admin_email'],
            'name' => $validated['admin_name'] ?? null,
            'role' => 'admin',
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);

        SendOrganizationInviteJob::dispatch($invite->id);

        $link = route('register', ['token' => $invite->token]);

        return back()->with('success', 'Organization created and admin invite queued. Fallback invite link: ' . $link);
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        if ($organization->users()->where('role', 'super_admin')->exists()) {
            return back()->with('error', 'Cannot delete organization that contains a super admin.');
        }

        $organization->delete();

        return back()->with('success', 'Organization deleted with related records.');
    }
}
