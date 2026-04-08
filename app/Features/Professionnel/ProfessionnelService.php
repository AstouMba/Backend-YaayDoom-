<?php

namespace App\Features\Professionnel;

use App\Application\Professionnel\CreateProfessionnel;
use App\Application\Professionnel\DeleteProfessionnel;
use App\Application\Professionnel\DTO\CreateProfessionnelData;
use App\Application\Professionnel\DTO\UpdateProfessionnelData;
use App\Application\Professionnel\GetProfessionnel;
use App\Application\Professionnel\ListProfessionnels;
use App\Application\Professionnel\UpdateProfessionnel;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ProfessionnelService extends Service
{
    public function __construct(
        private ListProfessionnels $listProfessionnels,
        private GetProfessionnel $getProfessionnel,
        private CreateProfessionnel $createProfessionnel,
        private UpdateProfessionnel $updateProfessionnel,
        private DeleteProfessionnel $deleteProfessionnel,
    ) {}

    /**
     * Récupérer tous les professionnels
     */
    public function getAll(): Collection
    {
        return $this->listProfessionnels->execute();
    }

    /**
     * Récupérer un professionnel par ID
     */
    public function get(string $id): ?User
    {
        return $this->getProfessionnel->execute($id);
    }

    /**
     * Créer un professionnel
     */
    public function create(array $data): User
    {
        return $this->createProfessionnel->execute(CreateProfessionnelData::fromArray($data));
    }

    /**
     * Mettre à jour un professionnel
     */
    public function update(string $id, array $data): ?User
    {
        return $this->updateProfessionnel->execute($id, UpdateProfessionnelData::fromArray($data));
    }

    /**
     * Supprimer un professionnel
     */
    public function delete(string $id): bool
    {
        return $this->deleteProfessionnel->execute($id);
    }
}
