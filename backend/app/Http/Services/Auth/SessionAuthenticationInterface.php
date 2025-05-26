<?php

namespace App\Http\Services\Auth;

use Illuminate\Http\JsonResponse;


interface SessionAuthenticationInterface
{
    /**
     * @param array $credentials
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(array $credentials): JsonResponse;

    /**
     * @param string|null $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(?string $token): JsonResponse;
}
