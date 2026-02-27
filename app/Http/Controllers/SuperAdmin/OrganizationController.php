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
use Throwable;

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

        $link = route('register', ['token' => $invite->token]);

        try {
            // Shared hosting often has no running queue worker; send immediately.
            SendOrganizationInviteJob::dispatchSync($invite->id);

            return back()->with('success', 'Organization created and admin invite sent. Invite link: ' . $link);
        } catch (Throwable $e) {
            $invite->forceFill([
                'send_error' => str($e->getMessage())->limit(1000)->toString(),
            ])->save();

            return back()->with('error', 'Organization created, but invite email failed to send. Invite link: ' . $link);
        }
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
