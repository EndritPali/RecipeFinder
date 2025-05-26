<?php

namespace  App\Http\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function __construct(protected UserRepository $userRepository) {}
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
