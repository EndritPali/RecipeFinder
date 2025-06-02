<?php

declare(strict_types=1);

namespace App\Repositories\PasswordReset;

use App\Models\User;
use Illuminate\Support\Collection;
use stdClass;

/**
 * Interface for password reset repository operations.
 */
interface PasswordResetRepositoryInterface
{
    /**
     * Update or insert a reset token for the user.
     *
     * @param int $userId
     * @param string $token
     * @param \DateTimeInterface|\Carbon\Carbon $expiresAt
     * @return bool
     */
    public function updateOrInsertResetToken(int $userId, string $token, $expiresAt): bool;

    /**
     * Find a password reset record by user ID and token.
     *
     * @param int $userId
     * @param string $token
     * @return stdClass|null
     */
    public function findResetRecord(int $userId, string $token): ?stdClass;

    /**
     * Delete a reset request by user ID.
     *
     * @param int $userId
     * @return int Number of records deleted
     */
    public function deleteResetByUser(int $userId): int;

    /**
     * Find a user by their username and email.
     *
     * @param string $username
     * @param string $email
     * @return stdClass|null
     */
    public function findUserByCredentials(string $username, string $email): ?stdClass;

    /**
     * Insert a manual password reset request.
     *
     * @param int $userId
     * @param string $requestData
     * @param \DateTimeInterface|\Carbon\Carbon $expiresAt
     * @return bool
     */
    public function insertResetRequest(int $userId, string $requestData, $expiresAt): bool;

    /**
     * Fetch pending password reset requests with associated user data.
     *
     * @return Collection<int, stdClass>
     */
    public function fetchPendingRequestsWithUsers(): Collection;

    /**
     * Find a reset record by its ID.
     *
     * @param int $id
     * @return stdClass|null
     */
    public function findResetById(int $id): ?stdClass;

    /**
     * Delete a reset request by its ID.
     *
     * @param int $id
     * @return int Number of records deleted
     */
    public function deleteResetById(int $id): int;

    /**
     * Find a User model by its ID.
     *
     * @param int $id
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findUserById(int $id): User;
}
