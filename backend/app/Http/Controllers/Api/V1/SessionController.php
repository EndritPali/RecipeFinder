<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\SessionLoginRequest;
use App\Http\Services\Auth\SessionAuthenticationInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller for handling session-based authentication endpoints.
 *
 * This controller follows the Single Responsibility Principle by focusing solely on
 * session-based authentication endpoints. It uses dependency injection for better
 * testability and follows SOLID principles.
 */
final class SessionController extends ApiController
{
    /**
     * @param SessionAuthenticationInterface $auth Authentication service instance
     */
    public function __construct(
        private readonly SessionAuthenticationInterface $auth
    ) {}

    /**
     * Handle user login.
     *
     * @param SessionLoginRequest $request Validated login request
     * @return JsonResponse Response containing authentication token and user data
     */
    public function login(SessionLoginRequest $request): JsonResponse
    {
        return $this->auth->login($request->validated());
    }

    /**
     * Handle user logout.
     *
     * @param Request $request HTTP request instance
     * @return JsonResponse Response indicating logout status
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
