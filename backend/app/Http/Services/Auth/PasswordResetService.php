<?php

namespace App\Http\Services\Auth;

use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetService
{
    /**
     * @param \App\Repositories\PasswordReset\PasswordResetRepositoryInterface $repo
     */
    public function __construct(protected PasswordResetRepositoryInterface $repo) {}

    /**
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function generateResetToken(array $data)
    {
        $token = Str::random(64);
        $expires = Carbon::now()->addMinutes(60);

        $this->repo->updateOrInsertResetToken($data['user_id'], $token, $expires);

        return response()->json([
            'status' => 'success',
            'message' => 'Reset token generated.',
            'reset_token' => $token,
        ]);
    }

    /**
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function performReset(array $data)
    {
        $record = $this->repo->findResetRecord($data['user_id'], $data['reset_token']);

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired token.'
            ], 422);
        }

        $user = $this->repo->findUserById($data['user_id']);
        $user->password_hash = Hash::make($data['password']);
        $user->save();

        $this->repo->deleteResetByUser($data['user_id']);

        return response()->json([
            'status' => 'success',
            'message' => 'Password has been reset.',
        ]);
    }

    /**
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function submitResetRequest(array $data)
    {
        $user = $this->repo->findUserByCredentials($data['username'], $data['email']);

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

        $this->repo->deleteResetByUser($user->id);
        $this->repo->insertResetRequest($user->id, $requestData, Carbon::now()->addDays(3));

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset request submitted.'
        ]);
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function fetchPendingRequests()
    {
        $requests = $this->repo->fetchPendingRequestsWithUsers();

        $formatted = $requests->map(function ($item) {
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
            'data' => $formatted
        ]);
    }

    /**
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function handleRequestProcessing(array $data)
    {
        $reset = $this->repo->findResetById($data['reset_id']);

        if (!$reset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reset request not found.'
            ], 404);
        }

        if ($data['action'] === 'approve') {
            $tempPassword = Str::random(10);
            $user = $this->repo->findUserById($reset->user_id);
            $user->password_hash = Hash::make($tempPassword);
            $user->save();

            $this->repo->deleteResetById($data['reset_id']);

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset approved.',
                'temporary_password' => $tempPassword,
                'user_email' => $user->email,
            ]);
        }

        $this->repo->deleteResetById($data['reset_id']);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset request denied.'
        ]);
    }
}
