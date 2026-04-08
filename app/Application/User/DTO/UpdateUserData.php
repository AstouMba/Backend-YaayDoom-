<?php

namespace App\Application\User\DTO;

readonly class UpdateUserData
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $password,
        public ?string $role,
        public ?string $status,
        public ?bool $isValidated,
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
            name: isset($data['name']) ? (string) $data['name'] : (isset($data['nom']) ? (string) $data['nom'] : null),
            email: isset($data['email']) ? (string) $data['email'] : null,
            phone: isset($data['phone']) ? (string) $data['phone'] : (isset($data['telephone']) ? (string) $data['telephone'] : null),
            password: isset($data['password']) ? (string) $data['password'] : null,
            role: isset($data['role']) ? (string) $data['role'] : null,
            status: isset($data['status']) ? (string) $data['status'] : (isset($data['statut']) ? (string) $data['statut'] : null),
            isValidated: array_key_exists('is_validated', $data) ? (bool) $data['is_validated'] : null,
            specialite: isset($data['specialite']) ? (string) $data['specialite'] : null,
            matricule: isset($data['matricule']) ? (string) $data['matricule'] : null,
            centreDeSante: isset($data['centre_de_sante']) ? (string) $data['centre_de_sante'] : null,
            rejectionReason: array_key_exists('rejection_reason', $data) ? (string) $data['rejection_reason'] : null,
        );
    }
}
