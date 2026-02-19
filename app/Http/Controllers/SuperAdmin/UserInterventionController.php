<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserInterventionController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->query('q');
        $organizationId = $request->query('organization_id');

        $users = User::query()
            ->withoutGlobalScope('organization')
            ->with('organization')
            ->when($q, fn ($qb) => $qb->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            }))
            ->when($organizationId, fn ($qb) => $qb->where('organization_id', (int) $organizationId))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('super_admin.users.index', compact('users', 'q', 'organizationId'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => 'required|string|in:voter,officer,admin,super_admin',
            'status' => 'required|string|in:active,suspended',
        ]);

        if ($user->id === auth()->id() && $data['role'] !== 'super_admin') {
            return back()->with('error', 'You cannot downgrade your own super admin account.');
        }

        $user->role = $data['role'];
        $user->status = $data['status'];
        $user->save();

        return back()->with('success', 'User intervention applied successfully.');
    }
}
