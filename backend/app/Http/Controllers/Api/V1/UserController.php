<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\Auth\UserService;

/**
 * @group Users
 * 
 * API endpoints for managing users
 */

class UserController extends Controller
{

    /**
     * The service responsible for user business logic
     * 
     * @var \App\Http\Services\Auth\UserService
     */
    protected UserService $userService;


    /**
     * Inject the UserService dependency
     * 
     * @param \App\Http\Services\Auth\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users
     * 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();

        return response()->json([
            'status' => 'success',
            'data' => UserResource::collection($users),
        ]);
    }

    /**
     * Stores the newly created user to the database
     * 
     * @param \App\Http\Requests\Api\V1\StoreUserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => new UserResource($user),
        ], 201);
    }

    /**
     * Displays the specified user
     * 
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $user = $this->userService->getUserById($id);

        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified user
     * 
     * @param \App\Http\Requests\Api\V1\UpdateUserRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified user
     * 
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ]);
    }
}
