<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\Auth\UserService;
use App\Repositories\Users\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Users
 *
 * API endpoints for managing users
 */
class UserController extends ApiController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request)
    {
        $response = $this->userService->getAll();

        if ($response->success()) {
            return UserResource::collection($response->getModel());
        }

        return response()->json(['message' => $response->getMessage()], 400);
    }

    /**
     * Store a newly created user.
     *
     * @param StoreUserRequest $request
     * @return UserResource|JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $response = $this->userService->store($request->validated());

        if ($response->success()) {
            return new UserResource($response->getModel());
        }

        return response()->json(['message' => $response->getMessage()], 400);
    }

    /**
     * Display the specified user.
     *
     * @param string $id
     * @return UserResource|JsonResponse
     */
    public function show(string $id)
    {
        $response = $this->userService->getById($id);

        if ($response->success()) {
            return new UserResource($response->getModel());
        }

        return response()->json(['message' => $response->getMessage()], 404);
    }

    /**
     * Update the specified user.
     *
     * @param UpdateUserRequest $request
     * @param string $id
     * @return UserResource|JsonResponse
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $response = $this->userService->update($id, $request->validated());

        if ($response->success()) {
            return new UserResource($response->getModel());
        }

        return response()->json(['message' => $response->getMessage()], 400);
    }

    /**
     * Remove the specified user.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        $response = $this->userService->destroy($id);

        if ($response->success()) {
            return response()->json(['message' => 'User deleted successfully.'], 200);
        }

        return response()->json(['message' => $response->getMessage()], 400);
    }
}
