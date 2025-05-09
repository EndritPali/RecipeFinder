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
}
