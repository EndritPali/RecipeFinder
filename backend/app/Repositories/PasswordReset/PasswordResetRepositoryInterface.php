<?php

namespace App\Repositories\PasswordReset;

use App\Models\User;

interface PasswordResetRepositoryInterface
{
    /**
     * @param int $userId
     * @param string $token
     * @param mixed $expiresAt
     * @return bool
     */
    public function updateOrInsertResetToken(int $userId, string $token, $expiresAt);

    /**
     * @param int $userId
     * @param string $token
     * @return object|null
     */
    public function findResetRecord(int $userId, string $token);

    /**
     * @param int $userId
     * @return int
     */
    public function deleteResetByUser(int $userId);

    /**
     * @param string $username
     * @param string $email
     * @return object|null
     */
    public function findUserByCredentials(string $username, string $email);

    /**
     * @param int $userId
     * @param string $requestData
     * @param mixed $expiresAt
     * @return bool
     */
    public function insertResetRequest(int $userId, string $requestData, $expiresAt);

    /**
     * @return \Illuminate\Support\Collection<int, \stdClass>
     */
    public function fetchPendingRequestsWithUsers();

    /**
     * @param int $id
     * @return object|null
     */
    public function findResetById(int $id);

    /**
     * @param int $id
     * @return int
     */
    public function deleteResetById(int $id);

    /**
     * @param int $id
     * @return User
     */
    public function findUserById(int $id);
}
