<?php

namespace App\Application\Consultation\DTO;

readonly class CreateConsultationData
{
    public function __construct(
        public string $mamanId,
        public string $professionnelId,
        public string $date,
        public string $heure,
        public string $type,
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
            mamanId: (string) $data['maman_id'],
            professionnelId: (string) $data['professionnel_id'],
            date: (string) $data['date'],
            heure: (string) $data['heure'],
            type: (string) $data['type'],
            tensionArterielle: isset($data['tension_arterielle']) ? (string) $data['tension_arterielle'] : null,
            poids: isset($data['poids']) ? (float) $data['poids'] : null,
            hauteurUterine: isset($data['hauteur_uterine']) ? (float) $data['hauteur_uterine'] : null,
            bcf: isset($data['bcf']) ? (string) $data['bcf'] : null,
            semaineGrossesse: isset($data['semaine_grossesse']) ? (int) $data['semaine_grossesse'] : null,
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
        );
    }
}
