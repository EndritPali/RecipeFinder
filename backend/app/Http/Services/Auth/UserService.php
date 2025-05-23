<?php

namespace App\Http\Services\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @param \App\Repositories\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** 
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    /** 
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        $data['password_hash'] = Hash::make($data['password']);
        unset($data['password']);

        return $this->userRepository->create($data);
    }

    /** 
     * @param string $id
     * @return User
     */
    public function getUserById(string $id): User
    {
        return $this->userRepository->findById($id);
    }

    /** 
     * @param string $id
     * @param array $data
     * @return User
     */
    public function updateUser(string $id, array $data): User
    {
        $user = $this->getUserById($id);

        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
        }
        unset($data['password']);

        $this->userRepository->update($user, $data);

        return $user;
    }

    /**
     * @param string $id
     * @return bool|null
     */
    public function deleteUser(string $id): bool
    {
        $user = $this->getUserById($id);
        return $this->userRepository->delete($user);
    }
}
