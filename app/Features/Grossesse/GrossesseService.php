<?php

namespace App\Features\Grossesse;

use App\Application\Grossesse\CreateGrossesse;
use App\Application\Grossesse\DeleteGrossesse;
use App\Application\Grossesse\DTO\CreateGrossesseData;
use App\Application\Grossesse\DTO\UpdateGrossesseData;
use App\Application\Grossesse\GetGrossesse;
use App\Application\Grossesse\ListGrossesses;
use App\Application\Grossesse\UpdateGrossesse;
use App\Application\Grossesse\ValidateGrossesse;
use App\Models\Grossesse;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class GrossesseService extends Service
{
    public function __construct(
        private ListGrossesses $listGrossesses,
        private GetGrossesse $getGrossesse,
        private CreateGrossesse $createGrossesse,
        private UpdateGrossesse $updateGrossesse,
        private ValidateGrossesse $validateGrossesse,
        private DeleteGrossesse $deleteGrossesse,
    ) {}

    /**
     * Récupérer toutes les grossesses
     */
    public function getAll(?User $user = null): Collection
    {
        return $this->listGrossesses->execute($user);
    }

    /**
     * Récupérer une grossesse par ID
     */
    public function get(string $id, ?User $user = null): ?Grossesse
    {
        return $this->getGrossesse->execute($id, $user);
    }

    /**
     * Créer une grossesse
     */
    public function create(array $data, ?User $user = null): Grossesse
    {
        $data['statut'] = $data['statut'] ?? 'en_attente';

        return $this->createGrossesse->execute(
            CreateGrossesseData::fromArray($data),
            $user
        );
    }

    /**
     * Mettre à jour une grossesse
     */
    public function update(string $id, array $data): ?Grossesse
    {
        return $this->updateGrossesse->execute($id, UpdateGrossesseData::fromArray($data));
    }

    /**
     * Valider explicitement une grossesse
     */
    public function validateGrossesse(string $id, User $user): Grossesse
    {
        return $this->validateGrossesse->execute($id, $user);
    }

    /**
     * Supprimer une grossesse
     */
    public function delete(string $id): bool
    {
        return $this->deleteGrossesse->execute($id);
    }
}
