<?php

namespace App\Features\Bebe;

use App\Application\Bebe\CreateBebe;
use App\Application\Bebe\DeleteBebe;
use App\Application\Bebe\DTO\CreateBebeData;
use App\Application\Bebe\DTO\UpdateBebeData;
use App\Application\Bebe\GetBebe;
use App\Application\Bebe\ListBebes;
use App\Application\Bebe\UpdateBebe;
use App\Models\Bebe;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class BebeService extends Service
{
    public function __construct(
        private ListBebes $listBebes,
        private GetBebe $getBebe,
        private CreateBebe $createBebe,
        private UpdateBebe $updateBebe,
        private DeleteBebe $deleteBebe,
    ) {}

    /**
     * Récupérer tous les bébés
     */
    public function getAll(): Collection
    {
        return $this->listBebes->execute();
    }

    /**
     * Récupérer un bébé par ID
     */
    public function get(string $id): ?Bebe
    {
        return $this->getBebe->execute($id);
    }

    /**
     * Créer un bébé
     */
    public function create(array $data): Bebe
    {
        return $this->createBebe->execute(CreateBebeData::fromArray($data));
    }

    /**
     * Mettre à jour un bébé
     */
    public function update(string $id, array $data): ?Bebe
    {
        return $this->updateBebe->execute($id, UpdateBebeData::fromArray($data));
    }

    /**
     * Supprimer un bébé
     */
    public function delete(string $id): bool
    {
        return $this->deleteBebe->execute($id);
    }
}
