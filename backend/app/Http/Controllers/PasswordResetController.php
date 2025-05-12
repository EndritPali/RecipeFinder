<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function requestReset(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(60);

        DB::table('password_resets')->where('user_id', $request->user_id)->delete();

        DB::table('password_resets')->insert([
            'user_id'     => $request->user_id,
            'reset_token' => $token,
            'expires_at'  => $expiresAt,
            'created_at'  => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reset token generated.',
            'reset_token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'reset_token' => 'required|string',
            'password'    => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_resets')
            ->where('user_id', $request->user_id)
            ->where('reset_token', $request->reset_token)
            ->first();

        if (!$reset || Carbon::parse($reset->expires_at)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired token.'
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        $user->password_hash = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('user_id', $request->user_id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password has been reset.',
        ]);
    }

    public function submitResetRequest(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'last_password' => 'required|string',
        ]);

        // Find user by username and email
        $user = DB::table('users')
            ->where('username', $request->username)
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found with the provided username and email.'
            ], 404);
        }

        // Store reset request in the existing password_resets table
        // We'll use reset_token to store the remembered password and status
        $requestData = json_encode([
            'last_password' => $request->last_password,
            'status' => 'pending'
        ]);

        // Delete any existing reset requests for this user
        DB::table('password_resets')->where('user_id', $user->id)->delete();

        // Create new reset request
        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'reset_token' => $requestData,
            'expires_at' => Carbon::now()->addDays(3), // Give admin time to review
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset request submitted. An administrator will review your request.'
        ]);
    }

    /**
     * Get all pending password reset requests (admin only)
     */
    public function getPendingRequests()
    {
        $requests = DB::table('password_resets')
            ->join('users', 'password_resets.user_id', '=', 'users.id')
            ->select(
                'password_resets.id',
                'users.id as user_id',
                'users.username',
                'users.email',
                'password_resets.reset_token',
                'password_resets.created_at'
            )
            ->get()
            ->map(function ($item) {
                $requestData = json_decode($item->reset_token);
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'username' => $item->username,
                    'email' => $item->email,
                    'last_password' => $requestData->last_password ?? 'Not provided',
                    'created_at' => $item->created_at
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $requests
        ]);
    }

    /**
     * Process a password reset request (admin only)
     */
    public function processResetRequest(Request $request)
    {
        $request->validate([
            'reset_id' => 'required|integer',
            'action' => 'required|in:approve,deny',
        ]);

        $resetRequest = DB::table('password_resets')
            ->where('id', $request->reset_id)
            ->first();

        if (!$resetRequest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reset request not found.'
            ], 404);
        }

        if ($request->action === 'approve') {
            // Generate temporary password
            $tempPassword = Str::random(10);

            // Update user's password
            $user = User::findOrFail($resetRequest->user_id);
            $user->password_hash = Hash::make($tempPassword);
            $user->save();

            // Delete the reset request
            DB::table('password_resets')->where('id', $request->reset_id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset approved.',
                'temporary_password' => $tempPassword, // Admin will send this to user
                'user_email' => $user->email
            ]);
        } else {
            // Just delete the reset request if denied
            DB::table('password_resets')->where('id', $request->reset_id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset request denied.'
            ]);
        }
    }
}
