<?php

namespace App\Application\Auth\DTO;

readonly class RegisterData
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $birthDate,
        public string $password,
        public string $role,
        public ?string $specialite,
        public ?string $matricule,
        public ?string $centreDeSante,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) ($data['name'] ?? $data['fullName'] ?? ''),
            email: ($data['email'] ?? null) ? (string) $data['email'] : null,
            phone: ($data['phone'] ?? null) ? (string) $data['phone'] : null,
            birthDate: ($data['birthDate'] ?? null) ? (string) $data['birthDate'] : null,
            password: (string) $data['password'],
            role: (string) ($data['role'] ?? 'maman'),
            specialite: ($data['specialite'] ?? $data['specialty'] ?? null) ? (string) ($data['specialite'] ?? $data['specialty']) : null,
            matricule: ($data['matricule'] ?? null) ? (string) $data['matricule'] : null,
            centreDeSante: ($data['centre_de_sante'] ?? $data['healthCenter'] ?? null) ? (string) ($data['centre_de_sante'] ?? $data['healthCenter']) : null,
        );
    }
}
