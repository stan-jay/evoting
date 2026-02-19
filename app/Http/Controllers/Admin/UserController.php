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
        $users = User::when($q, fn($qb) => $qb->where(function($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users', 'q'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role'   => 'required|string|in:voter,officer,admin',
            'status' => 'required|string|in:active,suspended',
        ]);

        // Use getKey() and Auth::id() for consistent identity checks
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
        // Prevent acting on yourself
        if (Auth::id() === $user->getKey()) {
            return back()->with('error', 'You cannot remove your own account here.');
        }

        $user->status = 'suspended';
        $user->save();

        return back()->with('success', 'User deactivated.');
    }
}
