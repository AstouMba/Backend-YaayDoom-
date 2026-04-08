<?php

namespace App\Application\Bebe\DTO;

readonly class UpdateBebeData
{
    public function __construct(
        public ?string $mamanId,
        public ?string $grossesseId,
        public ?string $nom,
        public ?string $dateNaissance,
        public ?string $sexe,
        public ?float $poids,
        public ?float $poidsActuel,
        public ?float $taille,
        public ?float $tailleActuelle,
        public ?string $groupeSanguin,
        public ?string $notes,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            mamanId: isset($data['maman_id']) ? (string) $data['maman_id'] : null,
            grossesseId: isset($data['grossesse_id']) ? (string) $data['grossesse_id'] : null,
            nom: isset($data['nom']) ? (string) $data['nom'] : null,
            dateNaissance: isset($data['date_naissance']) ? (string) $data['date_naissance'] : null,
            sexe: isset($data['sexe']) ? (string) $data['sexe'] : null,
            poids: isset($data['poids']) ? (float) $data['poids'] : null,
            poidsActuel: isset($data['poids_actuel']) ? (float) $data['poids_actuel'] : null,
            taille: isset($data['taille']) ? (float) $data['taille'] : null,
            tailleActuelle: isset($data['taille_actuelle']) ? (float) $data['taille_actuelle'] : null,
            groupeSanguin: isset($data['groupe_sanguin']) ? (string) $data['groupe_sanguin'] : null,
            notes: isset($data['notes']) ? (string) $data['notes'] : null,
        );
    }
}
