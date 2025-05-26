<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RequestReset;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use Illuminate\Http\Request;
use App\Http\Services\Auth\PasswordResetService;

class PasswordResetController extends Controller
{
    /**
     * @param \App\Http\Services\Auth\PasswordResetService $service
     */
    public function __construct(protected PasswordResetService $service) {}

    /**
     * @param \App\Http\Requests\Api\V1\RequestReset $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function requestReset(RequestReset $request)
    {
        return $this->service->generateResetToken($request->validated());
    }

    /**
     * @param \App\Http\Requests\Api\V1\ResetPasswordRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->service->performReset($request->validated());
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function submitResetRequest(Request $request)
    {
        return $this->service->submitResetRequest($request->all());
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getPendingRequests()
    {
        return $this->service->fetchPendingRequests();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function processResetRequest(Request $request)
    {
        return $this->service->handleRequestProcessing($request->all());
    }
}
