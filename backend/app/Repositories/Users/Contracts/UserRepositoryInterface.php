<?php

declare(strict_types=1);

namespace App\Repositories\Users\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface for user data persistence operations.
 *
 * This interface defines the contract for user repository implementations,
 * following the Repository pattern and Single Responsibility Principle.
 */
interface UserRepositoryInterface
{
    /**
     * Retrieve all users.
     *
     * @return Collection<int, User>
     */
    public function getAll(): Collection;

    /**
     * Find a user by their ID or throw an exception.
     *
     * @param string $id The unique identifier of the user
     * @throws ModelNotFoundException When user is not found
     * @return User The found user instance
     */
    public function findById(string $id): User;

    /**
     * Find a user by their ID without throwing an exception.
     *
     * @param string $id The unique identifier of the user
     * @return User|null The user instance if found, null otherwise
     */
    public function find(string $id): ?User;

    /**
     * Create a new user with the given data.
     *
     * @param array<string, mixed> $data The user data for creation
     * @throws \InvalidArgumentException When required fields are missing
     * @return User The newly created user instance
     */
    public function create(array $data): User;

    /**
     * Update an existing user with new data.
     *
     * @param User $user The user instance to update
     * @param array<string, mixed> $data The data to update the user with
     * @throws \InvalidArgumentException When invalid data is provided
     * @return bool True if update was successful, false otherwise
     */
    public function update(User $user, array $data): bool;

    /**
     * Delete a user from the system.
     *
     * @param User $user The user instance to delete
     * @throws \RuntimeException When deletion fails due to system error
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(User $user): bool;
}
