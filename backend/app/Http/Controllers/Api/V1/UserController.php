<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\Auth\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller for managing user resources.
 *
 * This controller handles HTTP requests for user management operations,
 * following REST principles and Single Responsibility Pattern.
 *
 * @group Users
 */
final class UserController extends ApiController
{
    /**
     * @param UserService $userService Service for user management operations
     */
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * List all users.
     *
     * Retrieves a paginated list of all users in the system.
     *
     * @return AnonymousResourceCollection|JsonResponse Collection of users or error response
     */
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        $response = $this->userService->getAll();

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 400);
        }

        return UserResource::collection($response->getModel());
    }

    /**
     * Create a new user.
     *
     * @param StoreUserRequest $request Validated request for user creation
     * @return UserResource|JsonResponse Created user resource or error response
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        $response = $this->userService->store($request->validated());

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 422);
        }

        return new UserResource($response->getModel());
    }

    /**
     * Display a specific user.
     *
     * @param string $id User ID
     * @return UserResource|JsonResponse User resource or error response
     */
    public function show(string $id): UserResource|JsonResponse
    {
        $response = $this->userService->getById($id);

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 404);
        }

        return new UserResource($response->getModel());
    }

    /**
     * Update a specific user.
     *
     * @param UpdateUserRequest $request Validated request for user update
     * @param string $id User ID
     * @return UserResource|JsonResponse Updated user resource or error response
     */
    public function update(UpdateUserRequest $request, string $id): UserResource|JsonResponse
    {
        $response = $this->userService->update($id, $request->validated());

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 422);
        }

        return new UserResource($response->getModel());
    }

    /**
     * Remove a specific user.
     *
     * @param string $id User ID
     * @return JsonResponse Success or error response
     */
    public function destroy(string $id): JsonResponse
    {
        $response = $this->userService->destroy($id);

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 500);
        }

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }


}
