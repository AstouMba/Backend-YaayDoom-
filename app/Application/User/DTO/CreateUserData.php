<?php

namespace App\Application\User\DTO;

readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $password,
        public string $role,
        public string $status,
        public bool $isValidated,
        public ?string $specialite,
        public ?string $matricule,
        public ?string $centreDeSante,
        public ?string $rejectionReason,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) ($data['name'] ?? $data['nom'] ?? ''),
            email: (string) $data['email'],
            phone: isset($data['phone']) ? (string) $data['phone'] : (isset($data['telephone']) ? (string) $data['telephone'] : null),
            password: (string) $data['password'],
            role: (string) ($data['role'] ?? 'user'),
            status: (string) ($data['status'] ?? $data['statut'] ?? 'actif'),
            isValidated: (bool) ($data['is_validated'] ?? true),
            specialite: isset($data['specialite']) ? (string) $data['specialite'] : null,
            matricule: isset($data['matricule']) ? (string) $data['matricule'] : null,
            centreDeSante: isset($data['centre_de_sante']) ? (string) $data['centre_de_sante'] : null,
            rejectionReason: isset($data['rejection_reason']) ? (string) $data['rejection_reason'] : null,
        );
    }
}
