<?php

namespace  App\Http\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    /**
     * @param \App\Repositories\UserRepository $userRepository
     */
    public function __construct(protected UserRepository $userRepository) {}

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
