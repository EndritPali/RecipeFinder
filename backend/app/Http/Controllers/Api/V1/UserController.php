<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => UserResource::collection(User::all()),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
        ]);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
        }

        unset($validated['password']);

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ]);
    }
}
