<?php

namespace App\Http\Services\Auth;

use App\Repositories\Users\Contracts\UserRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UserService
{
    /**
     * @var UserRepositoryInterface|null
     */
    private static ?UserRepositoryInterface $userRepository = null;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        self::$userRepository = $userRepository;
    }

    /**
     * Get the user repository instance.
     *
     * @return UserRepositoryInterface
     */
    private static function getRepository(): UserRepositoryInterface
    {
        if (!self::$userRepository) {
            self::$userRepository = app(UserRepositoryInterface::class);
        }
        return self::$userRepository;
    }

    /**
     * Retrieve all users.
     *
     * @return ServiceResponse
     */
    public static function getAll(): ServiceResponse
    {
        try {
            $users = self::getRepository()->getAll();
            return new ServiceResponse(true, $users);
        } catch (Exception $e) {
            Log::error('UserService::getAll Exception Error: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return ServiceResponse
     */
    public static function store(array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            if (isset($data['password'])) {
                $data['password_hash'] = Hash::make($data['password']);
                unset($data['password']);
            }

            $user = self::getRepository()->create($data);

            $user->refresh();

            DB::commit();

            return new ServiceResponse(true, $user);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::store Exception Error: ' . $e->getMessage());

            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Get a user by ID.
     *
     * @param string $id
     * @return ServiceResponse
     */
    public static function getById(string $id): ServiceResponse
    {
        try {
            $user = self::getRepository()->findById($id);
            return new ServiceResponse(true, $user);
        } catch (Exception $e) {
            Log::error('UserService::getById Exception Error: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Update an existing user.
     *
     * @param string $id
     * @param array $data
     * @return ServiceResponse
     */
    public static function update(string $id, array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = self::getRepository()->findById($id);

            if (isset($data['password'])) {
                $data['password_hash'] = Hash::make($data['password']);
                unset($data['password']);
            }

            $user->update($data);

            DB::commit();

            return new ServiceResponse(true, $user);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::update Exception Error: ' . $e->getMessage());

            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Delete a user by ID.
     *
     * @param string $id
     * @return ServiceResponse
     */
    public static function destroy(string $id): ServiceResponse
    {
        try {
            $user = self::getRepository()->findById($id);

            $deleted = self::getRepository()->delete($user);

            return new ServiceResponse($deleted, $deleted ? $user : null);
        } catch (Exception $e) {
            Log::error('UserService::destroy Exception Error: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
