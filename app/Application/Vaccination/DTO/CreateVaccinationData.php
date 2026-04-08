<?php

namespace App\Application\Vaccination\DTO;

readonly class CreateVaccinationData
{
    public function __construct(
        public string $bebeId,
        public string $nomVaccin,
        public ?string $age,
        public string $dateVaccination,
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
            bebeId: (string) $data['bebe_id'],
            nomVaccin: (string) $data['nom_vaccin'],
            age: isset($data['age']) ? (string) $data['age'] : null,
            dateVaccination: (string) $data['date_vaccination'],
            prochaineDose: isset($data['prochaine_dose']) ? (string) $data['prochaine_dose'] : null,
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
            professionnelId: isset($data['professionnel_id']) ? (string) $data['professionnel_id'] : null,
        );
    }
}
