<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class PasswordResetRepository
{
    /**
     * @param int $userId
     * @param string $token
     * @param mixed $expiresAt
     * @return bool
     */
    public function updateOrInsertResetToken(int $userId, string $token, $expiresAt)
    {
        return DB::table('password_resets')->updateOrInsert(
            ['user_id' => $userId],
            ['reset_token' => $token, 'expires_at' => $expiresAt, 'created_at' => now()]
        );
    }

    /**
     * @param int $userId
     * @param string $token
     * @return object|null
     */
    public function findResetRecord(int $userId, string $token)
    {
        return DB::table('password_resets')
            ->where('user_id', $userId)
            ->where('reset_token', $token)
            ->first();
    }

    /**
     * @param int $userId
     * @return int
     */
    public function deleteResetByUser(int $userId)
    {
        return DB::table('password_resets')->where('user_id', $userId)->delete();
    }

    /**
     * @param string $username
     * @param string $email
     * @return object|null
     */
    public function findUserByCredentials(string $username, string $email)
    {
        return DB::table('users')
            ->where('username', $username)
            ->where('email', $email)
            ->first();
    }

    /**
     * @param int $userId
     * @param string $requestData
     * @param mixed $expiresAt
     * @return bool
     */
    public function insertResetRequest(int $userId, string $requestData, $expiresAt)
    {
        return DB::table('password_resets')->insert([
            'user_id' => $userId,
            'reset_token' => $requestData,
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, \stdClass>
     */
    public function fetchPendingRequestsWithUsers()
    {
        return DB::table('password_resets')
            ->join('users', 'password_resets.user_id', '=', 'users.id')
            ->select('password_resets.id', 'users.id as user_id', 'users.username', 'users.email', 'password_resets.reset_token', 'password_resets.created_at')
            ->get();
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function findResetById(int $id)
    {
        return DB::table('password_resets')->where('id', $id)->first();
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteResetById(int $id)
    {
        return DB::table('password_resets')->where('id', $id)->delete();
    }

    /**
     * @param int $id
     * @return User
     */
    public function findUserById(int $id)
    {
        return User::findOrFail($id);
    }
}
