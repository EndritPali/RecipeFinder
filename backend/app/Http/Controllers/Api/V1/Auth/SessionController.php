<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SessionLoginRequest;
use App\Http\Services\Auth\SessionAuthenticationInterface;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    private SessionAuthenticationInterface $auth;

    public function __construct(SessionAuthenticationInterface $auth)
    {
        $this->auth = $auth;
    }

    public function login(SessionLoginRequest $request): JsonResponse
    {
        return $this->auth->login($request->validated());
    }

    public function logout(): JsonResponse
    {
        return $this->auth->logout(request()->bearerToken());
    }
}
