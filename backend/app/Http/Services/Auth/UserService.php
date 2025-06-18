<?php

declare(strict_types=1);

namespace App\Http\Services\Auth;

use App\Repositories\Users\Contracts\UserRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Service for handling user-related business logic.
 *
 * This service implements user management operations while maintaining
 * separation of concerns and following SOLID principles.
 */
final class UserService
{

    private static $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository Repository for user data persistence
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        self::$userRepository = $userRepository;
    }

    /**
     * Create a new user in the system.
     *
     * @param array<string, mixed> $data User data for creation
     * @return ServiceResponse<\App\Models\User>
     */
    public static function store(array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $data = self::processPassword($data);
            $user = self::$userRepository->create($data);

            DB::commit();
            return new ServiceResponse(true, $user);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('userslog')->error('Failed to create user', [
                'data' => Arr::except($data, ['password' => '']),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new ServiceResponse(false, null, 'Failed to create user');
        }
    }

    /**
     * Retrieve a specific user by their ID.
     *
     * @param string $id User ID
     * @return ServiceResponse<\App\Models\User>
     */
    public static function getById(string $id): ServiceResponse
    {
        try {
            $user = self::$userRepository->findById($id);
            return new ServiceResponse(true, $user);
        } catch (Throwable $e) {
            Log::channel('userslog')->error('Failed to retrieve user', [
                'id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new ServiceResponse(false, null, 'User not found');
        }
    }

    /**
     * Update an existing user's information.
     *
     * @param string $id User ID
     * @param array<string, mixed> $data Updated user data
     * @return ServiceResponse<\App\Models\User>
     */
    public static function update(string $id, array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = self::$userRepository->findById($id);
            $data = self::processPassword($data);

            $updated = self::$userRepository->update($user, $data);
            if (!$updated) {
                throw new \RuntimeException('Failed to update user');
            }

            $user = self::$userRepository->findById($id);

            DB::commit();
            return new ServiceResponse(true, $user);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('userslog')->error('Failed to update user', [
                'id' => $id,
                'data' => array_diff_key($data, ['password' => '']),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new ServiceResponse(false, null, 'Failed to update user');
        }
    }

    /**
     * Remove a user from the system.
     *
     * @param string $id User ID
     * @return ServiceResponse<null>
     */
    public static function destroy(string $id): ServiceResponse
    {
        try {
            $user = self::$userRepository->findById($id);
            $deleted = self::$userRepository->softDelete($user);

            if (!$deleted) {
                throw new \RuntimeException('Failed to delete user');
            }

            return new ServiceResponse(true, null, 'User deleted successfully');
        } catch (Throwable $e) {
            Log::channel('userslog')->error('Failed to delete user', [
                'id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new ServiceResponse(false, null, 'Failed to delete user');
        }
    }

    /**
     * Process password in the data array if present.
     *
     * @param array<string, mixed> $data Data array that might contain a password
     * @return array<string, mixed> Processed data array
     */
    private static function processPassword(array $data): array
    {
        if (isset($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }
        return $data;
    }
}
