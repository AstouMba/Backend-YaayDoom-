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
            email: isset($data['email']) ? (string) $data['email'] : null,
            phone: isset($data['phone']) ? (string) $data['phone'] : null,
            birthDate: isset($data['birthDate']) ? (string) $data['birthDate'] : null,
            password: (string) $data['password'],
            role: (string) ($data['role'] ?? 'maman'),
            specialite: isset($data['specialite']) ? (string) $data['specialite'] : (isset($data['specialty']) ? (string) $data['specialty'] : null),
            matricule: isset($data['matricule']) ? (string) $data['matricule'] : null,
            centreDeSante: isset($data['centre_de_sante']) ? (string) $data['centre_de_sante'] : (isset($data['healthCenter']) ? (string) $data['healthCenter'] : null),
        );
    }
}
