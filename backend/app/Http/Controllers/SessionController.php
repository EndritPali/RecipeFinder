<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Session;
use App\Models\User;

class SessionController extends Controller
{
    /**
     * Login a user and create a session
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password_hash)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        $token = Str::random(64);

        $session = Session::create([
            'user_id'  => $user->id,
            'token'  => $token,
            'expires_at' => now()->addDays(7),
            'created_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'token'  => $token,
            'user'   => $user,
        ]);
    }

    /**
     * Logout a user and destroy their session
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Token missing'], 400);
        }

        $deleted = Session::where('token', $token)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully',
            'deleted' => $deleted,
        ]);
    }
}
