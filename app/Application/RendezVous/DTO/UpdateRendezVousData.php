<?php

namespace App\Application\RendezVous\DTO;

readonly class UpdateRendezVousData
{
    public function __construct(
        public ?string $grossesseId,
        public ?string $professionnelId,
        public ?string $date,
        public ?string $heure,
        public ?string $type,
        public ?string $motif,
        public ?string $lieu,
        public ?string $notes,
        public ?string $statut,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            grossesseId: array_key_exists('grossesse_id', $data) ? (string) $data['grossesse_id'] : null,
            professionnelId: array_key_exists('professionnel_id', $data) ? (string) $data['professionnel_id'] : null,
            date: array_key_exists('date', $data) ? (string) $data['date'] : null,
            heure: array_key_exists('heure', $data) ? (string) $data['heure'] : null,
            type: array_key_exists('type', $data) ? (string) $data['type'] : null,
            motif: array_key_exists('motif', $data) ? (string) $data['motif'] : null,
            lieu: array_key_exists('lieu', $data) ? (string) $data['lieu'] : null,
            notes: array_key_exists('notes', $data) ? (string) $data['notes'] : null,
            statut: array_key_exists('statut', $data) ? (string) $data['statut'] : null,
        );
    }
}
