<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\DataTransferObjects\RegisterUserDTO;
use App\Models\User;
use App\Repositories\Users\Contracts\UserRepositoryInterface;
use App\Support\Classes\ServiceResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Service for handling user registration operations.
 *
 * This service implements user registration while maintaining separation
 * of concerns and following SOLID principles.
 */
final class RegisterService
{
      private static UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        self::$userRepository = $userRepository;
    }

    /**
     * Register a new user.
     *
     * @param RegisterUserDTO $dto Registration data transfer object
     * @return ServiceResponse<User> Response containing the created user or error
     */
    public static function register(RegisterUserDTO $dto): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = self::$userRepository->create([
                'username' => $dto->username,
                'email' => $dto->email,
                'password_hash' => Hash::make($dto->password),
                'role' => 'User',
            ]);

            DB::commit();
            return new ServiceResponse(true, $user);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to register user', [
                'username' => $dto->username,
                'email' => $dto->email,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new ServiceResponse(false, null, 'Registration failed');
        }
    }
}
