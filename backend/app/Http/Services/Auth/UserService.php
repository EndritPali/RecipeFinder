<?php

namespace App\Http\Services\Auth;

use App\Repositories\Users\Contracts\UserRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class UserService
 *
 * Handles all business logic related to user operations such as
 * creating, retrieving, updating, and deleting users.
 *
 * @package App\Http\Services\Auth
 */
class UserService
{
    /**
     * User repository for data access.
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieve all users.
     *
     * @return ServiceResponse
     */
    public function getAll(): ServiceResponse
    {
        try {
            $users = $this->userRepository->getAll();
            return new ServiceResponse(true, $users);
        } catch (Exception $e) {
            Log::error('UserService::getAll Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Store a new user in the database.
     *
     * @param array $data
     * @return ServiceResponse
     */
    public function store(array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            if (isset($data['password'])) {
                $data['password_hash'] = Hash::make($data['password']);
                unset($data['password']);
            }

            $user = $this->userRepository->create($data);

            DB::commit();
            return new ServiceResponse(true, $user);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::store Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Retrieve a user by their ID.
     *
     * @param string $id
     * @return ServiceResponse
     */
    public function getById(string $id): ServiceResponse
    {
        try {
            $user = $this->userRepository->findById($id);
            return new ServiceResponse(true, $user);
        } catch (Exception $e) {
            Log::error('UserService::getById Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Update a user by their ID.
     *
     * @param string $id
     * @param array $data
     * @return ServiceResponse
     */
    public function update(string $id, array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->findById($id);

            if (isset($data['password'])) {
                $data['password_hash'] = Hash::make($data['password']);
                unset($data['password']);
            }

            $updated = $this->userRepository->update($user, $data);

            DB::commit();
            if ($updated) {
                $user = $this->userRepository->findById($id);
                return new ServiceResponse(true, $user);
            }

            return new ServiceResponse(false, null, 'Update failed');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::update Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Delete a user by their ID.
     *
     * @param string $id
     * @return ServiceResponse
     */
    public function destroy(string $id): ServiceResponse
    {
        try {
            $user = $this->userRepository->findById($id);
            $deleted = $this->userRepository->delete($user);
            return new ServiceResponse($deleted, null, $deleted ? null : 'Deletion failed');
        } catch (Exception $e) {
            Log::error('UserService::destroy Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
