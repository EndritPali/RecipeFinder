<?php

declare(strict_types=1);

namespace App\Http\Services\Auth;

use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Service for handling password reset operations.
 */
final class PasswordResetService
{
    public function __construct(
        private readonly PasswordResetRepositoryInterface $repo
    ) {}

    /**
     * Generate a new reset token for the user.
     *
     * @param array{user_id: int} $data
     * @return JsonResponse
     */
    public function generateResetToken(array $data): JsonResponse
    {
        try {
            $token = Str::random(64);
            $expiresAt = now()->addMinutes(60);

            $this->repo->updateOrInsertResetToken($data['user_id'], $token, $expiresAt);

            return response()->json([
                'status' => 'success',
                'message' => 'Reset token generated.',
                'reset_token' => $token,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to generate reset token', [
                'user_id' => $data['user_id'],
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate reset token'
            ], 500);
        }
    }

    /**
     * Perform password reset using token and new password.
     *
     * @param array{user_id: int, reset_token: string, password: string} $data
     * @return JsonResponse
     */
    public function performReset(array $data): JsonResponse
    {
        try {
            $reset = $this->repo->findResetRecord($data['user_id'], $data['reset_token']);

            if (!$reset || Carbon::parse($reset->expires_at)->isPast()) {
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
        } catch (Throwable $e) {
            Log::error('Failed to reset password', [
                'user_id' => $data['user_id'],
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reset password'
            ], 500);
        }
    }

    /**
     * Submit a new password reset request for manual review.
     *
     * @param array{username: string, email: string, last_password: string} $data
     * @return JsonResponse
     */
    public function submitResetRequest(array $data): JsonResponse
    {
        try {
            $user = $this->repo->findUserByCredentials($data['username'], $data['email']);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ], 404);
            }

            $requestPayload = json_encode([
                'last_password' => $data['last_password'],
                'status' => 'pending'
            ]);

            $this->repo->deleteResetByUser($user->id);
            $this->repo->insertResetRequest($user->id, $requestPayload, now()->addDays(3));

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset request submitted.'
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to submit reset request', [
                'username' => $data['username'],
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit reset request'
            ], 500);
        }
    }

    /**
     * Fetch all pending password reset requests with related user info.
     *
     * @return JsonResponse
     */
    public function fetchPendingRequests(): JsonResponse
    {
        try {
            $requests = $this->repo->fetchPendingRequestsWithUsers();

            $formatted = $requests->map(function ($item) {
                $payload = json_decode($item->reset_token, false);
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'username' => $item->username,
                    'email' => $item->email,
                    'last_password' => $payload->last_password ?? 'Not provided',
                    'created_at' => $item->created_at,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $formatted
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to fetch pending requests', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch pending requests'
            ], 500);
        }
    }

    /**
     * Handle approval or denial of a reset request.
     *
     * @param array{reset_id: int, action: string} $data
     * @return JsonResponse
     */
    public function handleRequestProcessing(array $data): JsonResponse
    {
        try {
            $reset = $this->repo->findResetById((int) $data['reset_id']);

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

                $this->repo->deleteResetById($reset->id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Password reset approved.',
                    'temporary_password' => $tempPassword,
                    'user_email' => $user->email,
                ]);
            }

            $this->repo->deleteResetById($reset->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset request denied.'
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to process reset request', [
                'reset_id' => $data['reset_id'] ?? null,
                'action' => $data['action'] ?? null,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process reset request'
            ], 500);
        }
    }
}
