<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SessionLoginRequest;
use App\Http\Services\Auth\SessionAuthenticationInterface;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    /**
     * @var SessionAuthenticationInterface
     */
    private SessionAuthenticationInterface $auth;

    /**
     * @param \App\Http\Services\Auth\SessionAuthenticationInterface $auth
     */
    public function __construct(SessionAuthenticationInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param \App\Http\Requests\Api\V1\SessionLoginRequest $request
     * @return JsonResponse
     */
    public function login(SessionLoginRequest $request): JsonResponse
    {
        return $this->auth->login($request->validated());
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->auth->logout(request()->bearerToken());
    }
}
