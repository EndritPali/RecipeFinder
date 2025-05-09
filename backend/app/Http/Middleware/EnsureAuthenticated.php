<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // If no roles are defined, just confirm the user is authenticated
        if (empty($roles)) {
            return $next($request);
        }

        // Always check the role from the database user, never from client
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Forbidden - Insufficient role'], 403);
        }

        return $next($request);
    }
}
