<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Session\SessionRepository;

class EnsureToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized - No token provided'], 401);
        }

        $sessionRepo = app(SessionRepository::class);
        $session = $sessionRepo->findByToken($token);

        if (!$session || !$session->user || $session->expires_at <= now()) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $user = $session->user;
        if ($user instanceof \Illuminate\Contracts\Auth\Authenticatable) {
            Auth::login($user);
        }

        $request->setUserResolver(fn() => $session->user);

        return $next($request);
    }
}
