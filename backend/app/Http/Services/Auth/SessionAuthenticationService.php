<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Repositories\Session\SessionRepositoryInterface;

class SessionAuthenticationService implements SessionAuthenticationInterface
{
    protected SessionRepositoryInterface $sessions;

    /**
     * @param \App\Repositories\Session\SessionRepositoryInterface $sessions
     */
    public function __construct(SessionRepositoryInterface $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * @param array $credentials
     * @return JsonResponse
     */
    public function login(array $credentials): JsonResponse
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password_hash)) {
            return response()->json(['status' => 'error', 'message' => 'invalid credentials'], 401);
        }

        $token = Str::random(64);
        $this->sessions->create($user->id, $token);

        return response()->json([
            'status' => 'success',
            'token'  => $token,
            'user'   => $user,
        ]);
    }

    /**
     * @param string|null $token
     * @return JsonResponse
     */
    public function logout(?string $token): JsonResponse
    {
        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Token missing'], 400);
        }

        $deleted = $this->sessions->deleteByToken($token);

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully',
            'deleted' => $deleted,
        ]);
    }
}
