<?php

namespace App\Http\Services\Auth;

use Illuminate\Http\JsonResponse;


interface SessionAuthenticationInterface
{
    public function login(array $credentials): JsonResponse;
    public function logout(?string $token): JsonResponse;
}
