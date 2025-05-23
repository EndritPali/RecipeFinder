<?php

namespace App\Http\Services\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class PasswordResetService
{
    public function generateResetToken(array $data)
    {
        $token = Str::random(64);
        $expires = Carbon::now()->addMinutes(60);

        DB::table('password_resets')->updateOrInsert(
            ['user_id' => $data['user_id']],
            ['reset_token' => $token, 'expires_at' => $expires, 'created_at' => now()]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Reset token generated.',
            'reset_token' => $token,
        ]);
    }

    public function performReset(array $data)
    {
        $record = DB::table('password_resets')
            ->where('user_id', $data['user_id'])
            ->where('reset_token', $data['reset_token'])
            ->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired token.'
            ], 422);
        }

        $user = User::findOrFail($data['user_id']);
        $user->password_hash = Hash::make($data['password']);
        $user->save();

        DB::table('password_resets')->where('user_id', $data['user_id'])->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password has been reset.',
        ]);
    }

    public function submitResetRequest(array $data)
    {
        $user = DB::table('users')
            ->where('username', $data['username'])
            ->where('email', $data['email'])
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $requestData = json_encode([
            'last_password' => $data['last_password'],
            'status' => 'pending'
        ]);

        DB::table('password_resets')->where('user_id', $user->id)->delete();

        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'reset_token' => $requestData,
            'expires_at' => Carbon::now()->addDays(3),
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset request submitted.'
        ]);
    }

    public function fetchPendingRequests()
    {
        $requests = DB::table('password_resets')
            ->join('users', 'password_resets.user_id', '=', 'users.id')
            ->select('password_resets.id', 'users.id as user_id', 'users.username', 'users.email', 'password_resets.reset_token', 'password_resets.created_at')
            ->get()
            ->map(function ($item) {
                $data = json_decode($item->reset_token);
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'username' => $item->username,
                    'email' => $item->email,
                    'last_password' => $data->last_password ?? 'Not provided',
                    'created_at' => $item->created_at
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $requests
        ]);
    }

    public function handleRequestProcessing(array $data)
    {
        $reset = DB::table('password_resets')->where('id', $data['reset_id'])->first();

        if (!$reset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reset request not found.'
            ], 404);
        }

        if ($data['action'] === 'approve') {
            $tempPassword = Str::random(10);
            $user = User::findOrFail($reset->user_id);
            $user->password_hash = Hash::make($tempPassword);
            $user->save();

            DB::table('password_resets')->where('id', $data['reset_id'])->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset approved.',
                'temporary_password' => $tempPassword,
                'user_email' => $user->email,
            ]);
        }

        DB::table('password_resets')->where('id', $data['reset_id'])->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset request denied.'
        ]);
    }
}
