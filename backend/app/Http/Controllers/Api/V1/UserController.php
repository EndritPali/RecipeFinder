<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\Auth\UserService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Repositories\Users\Contracts\UserRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

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
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository Repository for user operations
     */
    public function __construct(
        UserRepositoryInterface $userRepository,

    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of users.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page') ? (int) $request->get('per_page') : null;
        $users = $this->userRepository->getPaginated($perPage);

        return UserResource::collection($users);
    }

    /**
     * Create a new user.
     *
     * @param StoreUserRequest $request Validated request for user creation
     * @return UserResource|JsonResponse Created user resource or error response
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        $response = UserService::store($request->validated());

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
        try {
            $user = $this->userRepository->findById($id);
            return new UserResource($user);
        } catch (\Exception $e) {
            return $this->errorResponse('User not found', 404);
        }
    }

    /**
     * Update a specific user.
     *
     * @param UpdateUserRequest $request Validated request for user update
     * @param User $user User ID
     * @return UserResource|JsonResponse Updated user resource or error response
     */
    public function update(UpdateUserRequest $request, User $user): UserResource|JsonResponse
    {
 
        $this->authorize('update', $user);

        $response = UserService::update($user->id, $request->validated());

        if (!$response->success()) {
            return $this->errorResponse($response->getMessage(), 422);
        }

        return new UserResource($response->getModel());
    }

    /**
     * Remove a specific user.
     *
     * @param User $user User ID
     * @return JsonResponse Success or error response
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->authorize('delete', $user);

            $response = UserService::destroy($user->id);

            if (!$response->success()) {
                return $this->errorResponse('Failed to delete user', 500);
            }

            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('User not found', 404);
        }
    }
}
