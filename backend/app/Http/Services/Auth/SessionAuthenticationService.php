<?php

namespace App\Http\Services\Auth;

use App\Models\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class SessionAuthenticationService implements SessionAuthenticationInterface
{
    public function login(array $credentials): JsonResponse
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password_hash)) {
            return response()->json(['status' => 'error', 'message' => 'invalid credentials'], 401);
        }

        $token = Str::random(64);

        $session = Session::create([
            'user_id'  => $user->id,
            'token'  => $token,
            'expires_at' => now()->addDays(7),
            'created_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'token'  => $token,
            'user'   => $user,
        ]);
    }

    public function logout(?string $token): JsonResponse
    {

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Token missing'], 400);
        }

        $deleted = Session::where('token', $token)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully',
            'deleted' => $deleted,
        ]);
    }
}
