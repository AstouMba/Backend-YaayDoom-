<?php

namespace App\Application\Professionnel\DTO;

readonly class CreateProfessionnelData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $password,
        public string $status,
        public bool $isValidated,
        public string $specialite,
        public string $matricule,
        public string $centreDeSante,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            email: (string) $data['email'],
            phone: isset($data['phone']) ? (string) $data['phone'] : null,
            password: (string) $data['password'],
            status: (string) ($data['status'] ?? 'actif'),
            isValidated: (bool) ($data['is_validated'] ?? false),
            specialite: (string) $data['specialite'],
            matricule: (string) $data['matricule'],
            centreDeSante: (string) $data['centre_de_sante'],
        );
    }
}
