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
use App\Models\User;
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
    public function getAll(?User $user = null): Collection
    {
        return $this->listRendezVous->execute($user);
    }

    /**
     * Récupérer un rendez-vous par ID
     */
    public function get(string $id, ?User $user = null): ?RendezVous
    {
        return $this->getRendezVous->execute($id, $user);
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
    public function update(string $id, array $data, ?User $user = null): ?RendezVous
    {
        return $this->updateRendezVous->execute($id, UpdateRendezVousData::fromArray($data), $user);
    }

    /**
     * Supprimer un rendez-vous
     */
    public function delete(string $id, ?User $user = null): bool
    {
        return $this->deleteRendezVous->execute($id, $user);
    }
}
