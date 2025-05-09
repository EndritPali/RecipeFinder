<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;

class EnsureToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized - No token provided'], 401);
        }

        // Get session with related user where token is valid
        $session = Session::with('user')->where('token', $token)->where('expires_at', '>', now())->first();

        if (!$session || !$session->user) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        // Authenticate user via Auth facade
        Auth::login($session->user);

        // Attach user to request manually
        $request->setUserResolver(fn() => $session->user);

        return $next($request);
    }
}
