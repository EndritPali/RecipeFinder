<?php

namespace App\Http\Services\Auth;

use Illuminate\Http\JsonResponse;

interface SessionAuthenticationInterface
{
    /**
     * Attempt to authenticate the user with provided credentials.
     *
     * @param array $credentials User credentials (e.g., email and password).
     * @return JsonResponse JSON response containing authentication status and token if successful.
     */
    public function login(array $credentials): JsonResponse;

    /**
     * Log out the user by invalidating the provided session token.
     *
     * @param string $token Session token to invalidate.
     * @return JsonResponse JSON response indicating logout success or failure.
     */
    public function logout(string $token): JsonResponse;
}
