<?php

declare(strict_types=1);

namespace App\Http\Services\Auth;

use Illuminate\Http\JsonResponse;

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
     * @throws \Illuminate\Auth\AuthenticationException When authentication fails due to system error
     * @return JsonResponse JSON response containing authentication status and token if successful
     */
    public function login(array $credentials): JsonResponse;

    /**
     * Log out the user by invalidating the provided session token.
     *
     * @param string|null $token Session token to invalidate
     * @throws \Illuminate\Auth\AuthenticationException When logout fails due to system error
     * @return JsonResponse JSON response indicating logout success or failure
     */
    public function logout(?string $token): JsonResponse;
}
