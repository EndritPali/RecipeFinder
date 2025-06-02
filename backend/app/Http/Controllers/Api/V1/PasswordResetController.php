<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RequestReset;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Http\Services\Auth\PasswordResetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller for handling password reset operations.
 */
final class PasswordResetController extends Controller
{
    public function __construct(
        private readonly PasswordResetService $service
    ) {}

    /**
     * Handle a password reset token request.
     * 
     * @param RequestReset $request
     * @return JsonResponse
     */
    public function requestReset(RequestReset $request): JsonResponse
    {
        return $this->service->generateResetToken($request->validated());
    }

    /**
     * Perform a password reset using the provided token and new password.
     * 
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->service->performReset($request->validated());
    }

    /**
     * Submit a password reset request for manual review.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function submitResetRequest(Request $request): JsonResponse
    {
        return $this->service->submitResetRequest($request->all());
    }

    /**
     * Fetch all pending reset requests with associated user info.
     * 
     * @return JsonResponse
     */
    public function getPendingRequests(): JsonResponse
    {
        return $this->service->fetchPendingRequests();
    }

    /**
     * Process an individual reset request (approve, deny, etc.).
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function processResetRequest(Request $request): JsonResponse
    {
        return $this->service->handleRequestProcessing($request->all());
    }
}
