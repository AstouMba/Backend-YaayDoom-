<?php

namespace App\Features\RendezVous;

use App\Application\RendezVous\CreateRendezVous;
use App\Application\RendezVous\DeleteRendezVous;
use App\Application\RendezVous\DTO\CreateRendezVousData;
use App\Application\RendezVous\DTO\UpdateRendezVousData;
use App\Application\RendezVous\GetRendezVous;
use App\Application\RendezVous\ListRendezVous;
use App\Application\RendezVous\UpdateRendezVous;
use App\Models\Grossesse;
use App\Models\RendezVous;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class RendezVousService extends Service
{
    public function __construct(
        private ListRendezVous $listRendezVous,
        private GetRendezVous $getRendezVous,
        private CreateRendezVous $createRendezVous,
        private UpdateRendezVous $updateRendezVous,
        private DeleteRendezVous $deleteRendezVous,
    ) {}

    /**
     * Récupérer tous les rendez-vous
     */
    public function getAll(): Collection
    {
        return $this->listRendezVous->execute();
    }

    /**
     * Récupérer un rendez-vous par ID
     */
    public function get(string $id): ?RendezVous
    {
        return $this->getRendezVous->execute($id);
    }

    /**
     * Créer un rendez-vous
     */
    public function create(array $data): RendezVous
    {
        $data['statut'] = $data['statut'] ?? 'prévu';

        return $this->createRendezVous->execute(CreateRendezVousData::fromArray($data));
    }

    /**
     * Mettre à jour un rendez-vous
     */
    public function update(string $id, array $data): ?RendezVous
    {
        return $this->updateRendezVous->execute($id, UpdateRendezVousData::fromArray($data));
    }

    /**
     * Supprimer un rendez-vous
     */
    public function delete(string $id): bool
    {
        return $this->deleteRendezVous->execute($id);
    }
}
