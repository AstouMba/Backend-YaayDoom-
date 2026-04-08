<?php

namespace App\Application\Professionnel\DTO;

readonly class UpdateProfessionnelData
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $password,
        public ?string $status,
        public ?bool $isValidated,
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
            name: isset($data['name']) ? (string) $data['name'] : null,
            email: isset($data['email']) ? (string) $data['email'] : null,
            phone: isset($data['phone']) ? (string) $data['phone'] : null,
            password: isset($data['password']) ? (string) $data['password'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
            isValidated: array_key_exists('is_validated', $data) ? (bool) $data['is_validated'] : null,
            specialite: isset($data['specialite']) ? (string) $data['specialite'] : null,
            matricule: isset($data['matricule']) ? (string) $data['matricule'] : null,
            centreDeSante: isset($data['centre_de_sante']) ? (string) $data['centre_de_sante'] : null,
        );
    }
}
