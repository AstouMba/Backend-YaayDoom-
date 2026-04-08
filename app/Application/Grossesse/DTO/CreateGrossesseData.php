<?php

namespace App\Application\Grossesse\DTO;

readonly class CreateGrossesseData
{
    public function __construct(
        public string $dateDebut,
        public ?string $dateFinPrevue,
        public int $nombreGrossessesPrecedentes,
        public ?string $antecedentsMedicaux,
        public ?string $professionnelValidateur,
        public ?string $dateValidation,
        public int $trimestre,
        public string $statut,
        public ?string $notes,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            dateDebut: (string) $data['date_debut'],
            dateFinPrevue: isset($data['date_fin_prevue']) ? (string) $data['date_fin_prevue'] : null,
            nombreGrossessesPrecedentes: (int) ($data['nombre_grossesses_precedentes'] ?? 0),
            antecedentsMedicaux: isset($data['antecedents_medicaux']) ? (string) $data['antecedents_medicaux'] : null,
            professionnelValidateur: isset($data['professionnel_validateur']) ? (string) $data['professionnel_validateur'] : null,
            dateValidation: isset($data['date_validation']) ? (string) $data['date_validation'] : null,
            trimestre: (int) ($data['trimestre'] ?? 1),
            statut: (string) ($data['statut'] ?? 'en_attente'),
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
        );
    }
}
