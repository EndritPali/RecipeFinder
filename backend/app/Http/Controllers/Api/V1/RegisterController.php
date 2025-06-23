<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\DataTransferObjects\RegisterUserDTO;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Services\RegisterService;
use Illuminate\Http\JsonResponse;

/**
 * Controller for handling user registration.
 *
 * This controller follows the Single Responsibility Principle by focusing solely on
 * user registration operations. It uses dependency injection for better testability.
 */
final class RegisterController extends ApiController
{
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request Validated registration request
     * @return JsonResponse Response containing the created user or error details
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterUserDTO::fromArray($request->validated());
        $response = RegisterService::register($dto);

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully!',
            'user' => $response->getModel(),
        ], 201);
    }
}
