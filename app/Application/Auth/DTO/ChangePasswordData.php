<?php

namespace App\Application\Auth\DTO;

readonly class ChangePasswordData
{
    public function __construct(
        public string $currentPassword,
        public string $newPassword,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            currentPassword: (string) $data['currentPassword'],
            newPassword: (string) $data['newPassword'],
        );
    }
}
