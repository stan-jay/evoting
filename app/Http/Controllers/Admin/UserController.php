<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $organizationId = Auth::user()?->organization_id;

        $users = User::query()
            ->where('organization_id', $organizationId)
            ->when($q, fn ($qb) => $qb->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureSameOrganization($user);

        $data = $request->validate([
            'role' => 'required|string|in:voter,officer,admin',
            'status' => 'required|string|in:active,suspended',
        ]);

        if ($user->getKey() === Auth::id() && $data['role'] !== 'admin') {
            return back()->with('error', 'You cannot change your own admin role.');
        }

        $user->role = $data['role'];
        $user->status = $data['status'];
        $user->save();

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->ensureSameOrganization($user);

        if (Auth::id() === $user->getKey()) {
            return back()->with('error', 'You cannot remove your own account here.');
        }

        $user->status = 'suspended';
        $user->save();

        return back()->with('success', 'User deactivated.');
    }

    private function ensureSameOrganization(User $user): void
    {
        if ((int) $user->organization_id !== (int) Auth::user()?->organization_id) {
            abort(404);
        }
    }
}
