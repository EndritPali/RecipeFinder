<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Handles data access logic for user entities.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return Collection<int, User>
     */
    public function getAll(): Collection
    {
        return User::all();
    }

    /**
     * Find specified user by ID or fail.
     *
     * @param string $id
     * @return User
     */
    public function findById(string $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Create a new user with the given data.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update user with new data.
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete the user.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
