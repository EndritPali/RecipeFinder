<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use App\Repositories\Session\SessionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class SessionAuthenticationService implements SessionAuthenticationInterface
{
    protected SessionRepositoryInterface $sessions;

    public function __construct(SessionRepositoryInterface $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * Attempt to authenticate user and create a session token.
     *
     * @param array $credentials
     * @return JsonResponse
     */
    public function login(array $credentials): JsonResponse
    {
        try {
            if (!$this->guard()->attempt($credentials)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
            }

            /** @var User $user */
            $user = $this->guard()->user();

            $token = Str::random(64);

            $this->sessions->create($user->id, $token);

            return response()->json([
                'status' => 'success',
                'token'  => $token,
                'user'   => $this->sanitizeUser($user),
            ]);
        } catch (Exception $e) {
            Log::error('SessionAuthenticationService::login Exception: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Authentication failed'], 500);
        }
    }

    /**
     * Logout by deleting the token from sessions.
     *
     * @param string|null $token
     * @return JsonResponse
     */
    public function logout(?string $token): JsonResponse
    {
        if (empty($token)) {
            return response()->json(['status' => 'error', 'message' => 'Token missing'], 400);
        }

        try {
            $deleted = $this->sessions->deleteByToken($token);

            if (!$deleted) {
                return response()->json(['status' => 'error', 'message' => 'Invalid token or already logged out'], 400);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Logged out successfully',
            ]);
        } catch (Exception $e) {
            Log::error('SessionAuthenticationService::logout Exception: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Logout failed'], 500);
        }
    }

    /**
     * Check if user is authenticated.
     *
     * @return bool
     */
    public function check(): bool
    {
        return $this->guard()->check();
    }

    /**
     * Get the authenticated user or null.
     *
     * @return User|null
     */
    public function user(): ?User
    {
        return $this->guard()->user();
    }

    /**
     * Get authenticated user ID or 0.
     *
     * @return int
     */
    public function id(): int
    {
        $user = $this->user();
        return $user ? $user->id : 0;
    }

    /**
     * Return a sanitized user array for API response.
     *
     * @param User $user
     * @return array
     */
    protected function sanitizeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }

    /**
     * Get the guard instance.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
