<?php

namespace App\Repositories\PasswordReset;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Collection;
use stdClass;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    /**
     * Update or insert a reset token for the user.
     * 
     * @param int $userId
     * @param string $token
     * @param mixed $expiresAt
     * @return bool
     */
    public function updateOrInsertResetToken(int $userId, string $token, $expiresAt): bool
    {
        return DB::table('password_resets')->updateOrInsert(
            ['user_id' => $userId],
            [
                'reset_token' => $token,
                'expires_at' => $expiresAt,
                'created_at' => now(),
            ]
        );
    }

    /**
     * Find a password reset record by user ID and token.
     * 
     * @param int $userId
     * @param string $token
     * @return stdClass|null
     */
    public function findResetRecord(int $userId, string $token): ?stdClass
    {
        return DB::table('password_resets')
            ->where('user_id', $userId)
            ->where('reset_token', $token)
            ->first();
    }

    /**
     * Delete a reset request by user ID.
     * 
     * @param int $userId
     * @return int
     */
    public function deleteResetByUser(int $userId): int
    {
        return DB::table('password_resets')->where('user_id', $userId)->delete();
    }

    /**
     * Find a user by their username and email.
     * 
     * @param string $username
     * @param string $email
     * @return stdClass|null
     */
    public function findUserByCredentials(string $username, string $email): ?stdClass
    {
        return DB::table('users')
            ->where('username', $username)
            ->where('email', $email)
            ->first();
    }

    /**
     * Insert a manual password reset request.
     * 
     * @param int $userId
     * @param string $requestData
     * @param mixed $expiresAt
     * @return bool
     */
    public function insertResetRequest(int $userId, string $requestData, $expiresAt): bool
    {
        return DB::table('password_resets')->insert([
            'user_id' => $userId,
            'reset_token' => $requestData,
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]);
    }

    /**
     * Fetch pending password reset requests with associated user data.
     *
     * @return Collection<int, stdClass>
     */
    public function fetchPendingRequestsWithUsers(): Collection
    {
        return DB::table('password_resets')
            ->join('users', 'password_resets.user_id', '=', 'users.id')
            ->select(
                'password_resets.id',
                'users.id as user_id',
                'users.username',
                'users.email',
                'password_resets.reset_token',
                'password_resets.created_at'
            )
            ->get();
    }

    /**
     * Find a reset record by its ID.
     * 
     * @param int $id
     * @return stdClass|null
     */
    public function findResetById(int $id): ?stdClass
    {
        return DB::table('password_resets')->where('id', $id)->first();
    }

    /**
     * Delete a reset request by its ID.
     * 
     * @param int $id
     * @return int
     */
    public function deleteResetById(int $id): int
    {
        return DB::table('password_resets')->where('id', $id)->delete();
    }

    /**
     * Find a User model by its ID.
     * 
     * @param int $id
     * @return User
     */
    public function findUserById(int $id): User
    {
        return User::findOrFail($id);
    }
}
