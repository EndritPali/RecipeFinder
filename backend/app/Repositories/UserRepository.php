<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Handles data access logic for user entities
 */
class UserRepository
{
    /**
     * Get all users
     * 
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Find specified user by id or fail
     * 
     * @param string $id
     * @return User
     */
    public function findById(string $id): ?User
    {
        return User::findOrFail($id);
    }

    /**
     * Create new user with the given data
     * 
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update user with new data
     * 
     * @param \App\Models\User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete user
     * 
     * @param \App\Models\User $user
     * @return bool|null
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
