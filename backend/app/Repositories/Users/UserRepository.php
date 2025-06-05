<?php

declare(strict_types=1);

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Users\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use RuntimeException;

/**
 * Implementation of the UserRepositoryInterface for Eloquent ORM.
 *
 * This repository handles all database operations for the User model,
 * following the Repository pattern and Single Responsibility Principle.
 */
final class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $model The User Eloquent model
     */
    public function __construct(
        private readonly User $model
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getAll(): Collection
    {
        return $this->model->newQuery()
            ->where('role', '!=', 'deleted')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function findById(string $id): User
    {
        try {
            return $this->model->newQuery()
                ->where('role', '!=', 'deleted')
                ->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(
                "User with ID {$id} not found.",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function find(string $id): ?User
    {
        return $this->model->newQuery()
            ->where('role', '!=', 'deleted')
            ->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): User
    {
        $requiredFields = ['email', 'username'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new InvalidArgumentException("Missing required field: {$field}");
            }
        }

        try {
            return $this->model->newQuery()->create($data);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to create user: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(User $user, array $data): bool
    {
        if (empty($data)) {
            throw new InvalidArgumentException('No data provided for update');
        }

        try {
            return $user->update($data);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to update user {$user->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(User $user): bool
    {
        try {
            return $user->delete();
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to delete user {$user->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function softDelete(User $user): bool
    {
        try {
            $anonymizedData = [
                'email' => "deleted_{$user->id}@deleted.user",
                'username' => "Deleted User",
                'role' => 'deleted',
                'remember_token' => null,
            ];

            return $user->update($anonymizedData);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to soft delete user {$user->id}: {$e->getMessage()}",
                previous: $e
            );
        }
    }
}
