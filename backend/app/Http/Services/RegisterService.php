<?php

namespace  App\Http\Services;

use App\Models\User;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return $this->userRepository->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role' => 'User',
        ]);
    }
}
