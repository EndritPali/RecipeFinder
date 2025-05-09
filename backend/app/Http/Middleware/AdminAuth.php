<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get user from request (this works because your EnsureToken middleware sets it)
        $user = $request->user();
        
        // If no user object is available, immediately reject
        if (!$user) {
            return response()->json(['message' => 'Authentication required'], 401);
        }
        
        // If user is logged in but not an admin, reject
        if ($user->role !== 'Admin') {
            return response()->json(['message' => 'Admin privileges required'], 403);
        }
        
        return $next($request);
    }
}