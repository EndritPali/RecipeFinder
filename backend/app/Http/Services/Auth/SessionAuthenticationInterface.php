<?php

declare(strict_types=1);

namespace App\Http\Services\Auth;

use Illuminate\Http\JsonResponse;
use App\Support\Classes\ServiceResponse;

/**
 * Interface for handling session-based authentication operations.
 *
 * This interface defines the contract for authentication services that manage
 * user sessions using tokens. It follows the Single Responsibility Principle
 * by focusing solely on session-based authentication operations.
 */
interface SessionAuthenticationInterface
{
    /**
     * Attempt to authenticate the user with provided credentials.
     *
     * @param array<string, mixed> $credentials User credentials (typically email and password)
     * @return ServiceResponse
     */
    public static function login(array $credentials): ServiceResponse;

    /**
     * Log out the user by invalidating the provided session token.
     *
     * @param string|null $token Session token to invalidate
     * @return ServiceResponse
     */
    public static function logout(?string $token): ServiceResponse;
}
