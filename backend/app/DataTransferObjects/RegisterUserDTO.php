<?php

namespace App\DataTransferObjects;

final class RegisterUserDTO
{
    public function __construct(
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            username: $data['username'],
            email: $data['email'],
            password: $data['password']
        );
    }
}
