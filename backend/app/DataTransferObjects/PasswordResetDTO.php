<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

final class PasswordResetDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly ?string $resetToken = null,
        public readonly ?string $password = null,
        public readonly ?string $username = null,
        public readonly ?string $email = null,
        public readonly ?string $lastPassword = null,
    ) {}

    /**
     * Create DTO from array data.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: (int) ($data['user_id'] ?? 0),
            resetToken: $data['reset_token'] ?? null,
            password: $data['password'] ?? null,
            username: $data['username'] ?? null,
            email: $data['email'] ?? null,
            lastPassword: $data['last_password'] ?? null,
        );
    }
}
