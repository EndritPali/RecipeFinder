<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * @param \App\Http\Services\Auth\RegisterService $service
     */
    public function __construct(protected RegisterService $service) {}

    /**
     * @param \App\Http\Requests\Api\V1\RegisterRequest $request
     * @return JsonResponse|mixed
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->service->register($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully!',
            'user' => $user,
        ]);
    }
}
