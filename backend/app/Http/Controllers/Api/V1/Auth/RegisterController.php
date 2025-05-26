<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(protected RegisterService $service) {}

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
