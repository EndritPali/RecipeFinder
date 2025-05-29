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
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

    public function store(array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            if (isset($data['password'])) {
                $data['password_hash'] = Hash::make($data['password']);
                unset($data['password']);
            }

            $user = $this->userRepository->create($data);
            $user->refresh();

            DB::commit();
            return new ServiceResponse(true, $user);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::store Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

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

    public function update(string $id, array $data): ServiceResponse
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->findById($id);

            if (isset($data['password'])) {
                $data['password_hash'] = Hash::make($data['password']);
                unset($data['password']);
            }

            $user->update($data);

            DB::commit();
            return new ServiceResponse(true, $user);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserService::update Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }

    public function destroy(string $id): ServiceResponse
    {
        try {
            $user = $this->userRepository->findById($id);
            $deleted = $this->userRepository->delete($user);
            return new ServiceResponse($deleted, $deleted ? $user : null);
        } catch (Exception $e) {
            Log::error('UserService::destroy Exception: ' . $e->getMessage());
            return new ServiceResponse(false, null, $e->getMessage());
        }
    }
}
