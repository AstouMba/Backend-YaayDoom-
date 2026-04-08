<?php

namespace App\Application\Auth\DTO;

readonly class UpdateProfileData
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $specialite,
        public ?string $centreDeSante,
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
            specialite: isset($data['specialite']) ? (string) $data['specialite'] : null,
            centreDeSante: isset($data['centre_de_sante']) ? (string) $data['centre_de_sante'] : null,
        );
    }
}
