<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\SessionLoginRequest;
use App\Http\Services\Auth\SessionAuthenticationService;
use App\Support\Classes\ServiceResponse;
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
     * Handle user login.
     *
     * @param SessionLoginRequest $request Validated login request
     * @return JsonResponse Response containing authentication token and user data
     */
    public function login(SessionLoginRequest $request): JsonResponse
    {
        $response = SessionAuthenticationService::login($request->validated());
        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 401);
        }
        return response()->json([
            'status' => 'success',
            'token' => $response->getMessage(),
            'user' => $response->getModel(),
        ]);
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
            return $this->errorResponse('Authorization token missing', 400);
        }
        $response = SessionAuthenticationService::logout($token);
        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => $response->getMessage(),
        ]);
    }
}
