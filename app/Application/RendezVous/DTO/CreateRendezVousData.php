<?php

namespace App\Application\RendezVous\DTO;

readonly class CreateRendezVousData
{
    public function __construct(
        public string $grossesseId,
        public string $type,
        public string $motif,
        public string $date,
        public string $heure,
        public string $professionnelId,
        public string $lieu,
        public ?string $notes,
        public string $statut,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            grossesseId: (string) $data['grossesse_id'],
            type: (string) $data['type'],
            motif: (string) $data['motif'],
            date: (string) $data['date'],
            heure: (string) $data['heure'],
            professionnelId: (string) $data['professionnel_id'],
            lieu: (string) $data['lieu'],
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
            statut: (string) ($data['statut'] ?? 'prévu'),
        );
    }
}
