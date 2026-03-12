<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! auth()->check()) {
            abort(403);
        }

        $user = auth()->user();

        if ($user->status !== 'active') {
            abort(403);
        }

        // Super admin can pass role checks on all guarded route groups.
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
