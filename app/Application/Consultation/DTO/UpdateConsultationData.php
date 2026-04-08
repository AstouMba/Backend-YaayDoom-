<?php

namespace App\Application\Consultation\DTO;

readonly class UpdateConsultationData
{
    public function __construct(
        public ?string $mamanId,
        public ?string $professionnelId,
        public ?string $date,
        public ?string $heure,
        public ?string $type,
        public ?string $tensionArterielle,
        public ?float $poids,
        public ?float $hauteurUterine,
        public ?string $bcf,
        public ?int $semaineGrossesse,
        public ?string $notes,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            mamanId: isset($data['maman_id']) ? (string) $data['maman_id'] : null,
            professionnelId: isset($data['professionnel_id']) ? (string) $data['professionnel_id'] : null,
            date: isset($data['date']) ? (string) $data['date'] : null,
            heure: isset($data['heure']) ? (string) $data['heure'] : null,
            type: isset($data['type']) ? (string) $data['type'] : null,
            tensionArterielle: isset($data['tension_arterielle']) ? (string) $data['tension_arterielle'] : null,
            poids: isset($data['poids']) ? (float) $data['poids'] : null,
            hauteurUterine: isset($data['hauteur_uterine']) ? (float) $data['hauteur_uterine'] : null,
            bcf: isset($data['bcf']) ? (string) $data['bcf'] : null,
            semaineGrossesse: isset($data['semaine_grossesse']) ? (int) $data['semaine_grossesse'] : null,
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
        );
    }
}
