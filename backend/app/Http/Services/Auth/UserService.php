<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * UserService constructor.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieve all users.
     *
     * @return Collection<int, User>
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User|null
     */
    public function createUser(array $data): ?User
    {
        try {
            DB::beginTransaction();

            $data['password_hash'] = Hash::make($data['password'] ?? '');
            unset($data['password']);

            $user = $this->userRepository->create($data);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::createUser Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a user by ID.
     *
     * @param string $id
     * @return User|null
     */
    public function getUserById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Update an existing user.
     *
     * @param string $id
     * @param array $data
     * @return User|null
     */
    public function updateUser(string $id, array $data): ?User
    {
        try {
            DB::beginTransaction();

            $user = $this->getUserById($id);
            if (!$user) {
                return null;
            }

            if (!empty($data['password'])) {
                $data['password_hash'] = Hash::make($data['password']);
            }

            unset($data['password']);

            $this->userRepository->update($user, $data);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::updateUser Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a user by ID.
     *
     * @param string $id
     * @return bool
     */
    public function deleteUser(string $id): bool
    {
        try {
            $user = $this->getUserById($id);
            if (!$user) {
                return false;
            }

            return $this->userRepository->delete($user);
        } catch (Exception $e) {
            Log::error('UserService::deleteUser Error: ' . $e->getMessage());
            return false;
        }
    }
}
