<?php

namespace App\Application\Grossesse\DTO;

readonly class UpdateGrossesseData
{
    public function __construct(
        public ?string $mamanId,
        public ?string $dateDebut,
        public ?string $dateFinPrevue,
        public ?int $nombreGrossessesPrecedentes,
        public ?string $antecedentsMedicaux,
        public ?string $professionnelValidateur,
        public ?string $dateValidation,
        public ?int $trimestre,
        public ?string $statut,
        public ?string $notes,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            mamanId: isset($data['maman_id']) ? (string) $data['maman_id'] : null,
            dateDebut: isset($data['date_debut']) ? (string) $data['date_debut'] : null,
            dateFinPrevue: isset($data['date_fin_prevue']) ? (string) $data['date_fin_prevue'] : null,
            nombreGrossessesPrecedentes: isset($data['nombre_grossesses_precedentes']) ? (int) $data['nombre_grossesses_precedentes'] : null,
            antecedentsMedicaux: isset($data['antecedents_medicaux']) ? (string) $data['antecedents_medicaux'] : null,
            professionnelValidateur: isset($data['professionnel_validateur']) ? (string) $data['professionnel_validateur'] : null,
            dateValidation: isset($data['date_validation']) ? (string) $data['date_validation'] : null,
            trimestre: isset($data['trimestre']) ? (int) $data['trimestre'] : null,
            statut: isset($data['statut']) ? (string) $data['statut'] : null,
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
        );
    }
}
