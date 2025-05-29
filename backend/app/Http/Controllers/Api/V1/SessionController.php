<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\SessionLoginRequest;
use App\Http\Services\Auth\SessionAuthenticationInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends ApiController
{
    private SessionAuthenticationInterface $auth;

    public function __construct(SessionAuthenticationInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle user login.
     *
     * @param SessionLoginRequest $request
     * @return JsonResponse
     */
    public function login(SessionLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        return $this->auth->login($credentials);
    }

    /**
     * Handle user logout.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authorization token missing',
            ], 400);
        }

        return $this->auth->logout($token);
    }
}
