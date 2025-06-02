<?php

declare(strict_types=1);

namespace App\Http\Services\Auth;

use App\Models\User;
use App\Repositories\Session\SessionRepositoryInterface;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;
use Throwable;

/**
 * Service class for handling session-based authentication operations.
 *
 * This service implements the SessionAuthenticationInterface and follows the Single
 * Responsibility Principle by focusing solely on session-based authentication operations.
 * It uses dependency injection for better testability and follows SOLID principles.
 */
final class SessionAuthenticationService implements SessionAuthenticationInterface
{
    /**
     * @param SessionRepositoryInterface $sessions Session repository instance
     */
    public function __construct(
        private readonly SessionRepositoryInterface $sessions
    ) {}

    /**
     * {@inheritDoc}
     */
    public function login(array $credentials): JsonResponse
    {
        try {
            if (!$this->guard()->attempt($credentials)) {
                return $this->errorResponse('Invalid credentials', 401);
            }

            /** @var User $user */
            $user = $this->guard()->user();

            if (!$user) {
                throw new AuthenticationException('Failed to retrieve authenticated user');
            }

            $token = $this->generateToken();
            $this->sessions->create($user->id, $token);

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $this->sanitizeUser($user),
            ]);
        } catch (Throwable $e) {
            Log::error('SessionAuthenticationService::login Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Authentication failed', 500);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function logout(?string $token): JsonResponse
    {
        if (empty($token)) {
            return $this->errorResponse('Token missing', 400);
        }

        try {
            $deleted = $this->sessions->deleteByToken($token);

            if (!$deleted) {
                return $this->errorResponse('Invalid token or already logged out', 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('SessionAuthenticationService::logout Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Logout failed', 500);
        }
    }

    /**
     * Check if user is authenticated.
     */
    public function check(): bool
    {
        return $this->guard()->check();
    }

    /**
     * Get the authenticated user or null.
     */
    public function user(): ?User
    {
        return $this->guard()->user();
    }

    /**
     * Get authenticated user ID or 0.
     */
    public function id(): int
    {
        return $this->user()?->id ?? 0;
    }

    /**
     * Return a sanitized user array for API response.
     *
     * @param User $user User instance to sanitize
     * @return array<string, mixed> Sanitized user data
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
     */
    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    /**
     * Generate a secure random token.
     */
    protected function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Create an error response with the given message and status code.
     *
     * @param string $message Error message
     * @param int $status HTTP status code
     */
    protected function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $status);
    }
}
