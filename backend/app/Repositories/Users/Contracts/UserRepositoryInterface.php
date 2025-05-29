<?php

namespace App\Repositories\Users\Contracts;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 *
 * Defines the contract for interacting with user data.
 *
 * @package App\Repositories\Users
 */
interface UserRepositoryInterface
{
    /**
     * Retrieve all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function getAll();

    /**
     * Find a user by their ID.
     *
     * @param string $id The ID of the user.
     * @return User|null The user if found, null otherwise.
     */
    public function findById(string $id): ?User;

    /**
     * Create a new user.
     *
     * @param array $data The data to create the user with.
     * @return User The newly created user.
     */
    public function create(array $data): User;

    /**
     * Update an existing user.
     *
     * @param User $user The user to update.
     * @param array $data The data to update the user with.
     * @return bool True on success, false on failure.
     */
    public function update(User $user, array $data): bool;

    /**
     * Delete a user.
     *
     * @param User $user The user to delete.
     * @return bool True if deletion was successful, false otherwise.
     */
    public function delete(User $user): bool;
}
