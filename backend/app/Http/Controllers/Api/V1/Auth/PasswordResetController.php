<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RequestReset;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use Illuminate\Http\Request;
use App\Http\Services\Auth\PasswordResetService;

class PasswordResetController extends Controller
{
    public function __construct(protected PasswordResetService $service) {}

    public function requestReset(RequestReset $request)
    {
        return $this->service->generateResetToken($request->validated());
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->service->performReset($request->validated());
    }

    public function submitResetRequest(Request $request)
    {
        return $this->service->submitResetRequest($request->all());
    }

    public function getPendingRequests()
    {
        return $this->service->fetchPendingRequests();
    }

    public function processResetRequest(Request $request)
    {
        return $this->service->handleRequestProcessing($request->all());
    }
}
