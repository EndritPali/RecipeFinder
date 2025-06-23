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
use App\Support\Classes\ServiceResponse;

/**
 * Service class for handling session-based authentication operations.
 *
 * This service implements the SessionAuthenticationInterface and follows the Single
 * Responsibility Principle by focusing solely on session-based authentication operations.
 * It uses dependency injection for better testability and follows SOLID principles.
 */
final class SessionAuthenticationService implements SessionAuthenticationInterface
{
    private static SessionRepositoryInterface $sessions;

    public function __construct(SessionRepositoryInterface $sessions)
    {
        self::$sessions = $sessions;
    }

    /**
     * {@inheritDoc}
     */
    public static function login(array $credentials): ServiceResponse
    {
        try {
            if (!self::guard()->attempt($credentials)) {
                return new ServiceResponse(false, null, 'Invalid credentials');
            }

            /** @var User $user */
            $user = self::guard()->user();

            if (!$user) {
                return new ServiceResponse(false, null, 'Failed to retrieve authenticated user');
            }

            $token = self::generateToken();
            self::$sessions->create($user->id, $token);

            return new ServiceResponse(true, $user, $token);
        } catch (Throwable $e) {
            Log::channel('sessionlog')->error('SessionAuthenticationService::login Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return new ServiceResponse(false, null, 'Authentication failed');
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function logout(?string $token): ServiceResponse
    {
        if (empty($token)) {
            return new ServiceResponse(false, null, 'Token missing');
        }

        try {
            $deleted = self::$sessions->deleteByToken($token);

            if (!$deleted) {
                return new ServiceResponse(false, null, 'Invalid token or already logged out');
            }

            return new ServiceResponse(true, null, 'Logged out successfully');
        } catch (Throwable $e) {
            Log::channel('sessionlog')->error('SessionAuthenticationService::logout Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return new ServiceResponse(false, null, 'Logout failed');
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
    protected static function sanitizeUser(User $user): array
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
    protected static function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    /**
     * Generate a secure random token.
     */
    protected static function generateToken(): string
    {
        return Str::random(64);
    }
}
