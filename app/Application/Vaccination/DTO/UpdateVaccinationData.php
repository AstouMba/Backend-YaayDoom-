<?php

namespace App\Application\Vaccination\DTO;

readonly class UpdateVaccinationData
{
    public function __construct(
        public ?string $bebeId,
        public ?string $nomVaccin,
        public ?string $age,
        public ?string $dateVaccination,
        public ?string $prochaineDose,
        public ?string $notes,
        public ?string $professionnelId,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            bebeId: isset($data['bebe_id']) ? (string) $data['bebe_id'] : null,
            nomVaccin: isset($data['nom_vaccin']) ? (string) $data['nom_vaccin'] : null,
            age: isset($data['age']) ? (string) $data['age'] : null,
            dateVaccination: isset($data['date_vaccination']) ? (string) $data['date_vaccination'] : null,
            prochaineDose: isset($data['prochaine_dose']) ? (string) $data['prochaine_dose'] : null,
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
            professionnelId: isset($data['professionnel_id']) ? (string) $data['professionnel_id'] : null,
        );
    }
}
