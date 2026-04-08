<?php

namespace App\Features\Famille;

use App\Application\Famille\GetFamily;
use App\Services\Service;

class FamilleService extends Service
{
    public function __construct(
        private GetFamily $getFamily,
    ) {}

    /**
     * Récupérer un dossier familial complet à partir de la maman.
     *
     * @return array<string, mixed>|null
     */
    public function get(string $mamanId): ?array
    {
        return $this->getFamily->execute($mamanId);
    }

    /**
     * Récupérer le bloc maman du dossier.
     *
     * @return array<string, mixed>|null
     */
    public function getMaman(string $mamanId): ?array
    {
        return $this->getFamily->maman($mamanId);
    }

    /**
     * Récupérer le bloc bébé du dossier.
     *
     * @return array<string, mixed>|null
     */
    public function getBebe(string $mamanId, string $bebeId): ?array
    {
        return $this->getFamily->bebe($mamanId, $bebeId);
    }
}
