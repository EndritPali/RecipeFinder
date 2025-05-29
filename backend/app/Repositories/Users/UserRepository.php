<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Users\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Handles data access logic for user entities.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    private User $model;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get all users.
     *
     * @return Collection<int, User>
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find specified user by ID or fail.
     *
     * @param string $id
     * @return User
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(string $id): User
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find a user by ID or return null.
     *
     * @param string $id
     * @return User|null
     */
    public function find(string $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Create a new user with the given data.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
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
