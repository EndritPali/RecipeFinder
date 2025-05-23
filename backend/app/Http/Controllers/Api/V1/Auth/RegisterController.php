<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'username' => $request['username'],
            'email' => $request['email'],
            'password_hash' => bcrypt($request['password']),
            'role' => 'User',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully!',
            'user' => $user,
        ]);
    }
}
