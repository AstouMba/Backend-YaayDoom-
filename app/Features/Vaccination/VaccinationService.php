<?php

namespace App\Features\Vaccination;

use App\Application\Vaccination\CreateVaccination;
use App\Application\Vaccination\DeleteVaccination;
use App\Application\Vaccination\DTO\CreateVaccinationData;
use App\Application\Vaccination\DTO\UpdateVaccinationData;
use App\Application\Vaccination\GetVaccination;
use App\Application\Vaccination\ListVaccinations;
use App\Application\Vaccination\UpdateVaccination;
use App\Models\Vaccination;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class VaccinationService extends Service
{
    public function __construct(
        private ListVaccinations $listVaccinations,
        private GetVaccination $getVaccination,
        private CreateVaccination $createVaccination,
        private UpdateVaccination $updateVaccination,
        private DeleteVaccination $deleteVaccination,
    ) {}

    /**
     * Récupérer toutes les vaccinations
     */
    public function getAll(): Collection
    {
        return $this->listVaccinations->execute();
    }

    /**
     * Récupérer une vaccination par ID
     */
    public function get(string $id): ?Vaccination
    {
        return $this->getVaccination->execute($id);
    }

    /**
     * Créer une vaccination
     */
    public function create(array $data): Vaccination
    {
        return $this->createVaccination->execute(CreateVaccinationData::fromArray($data));
    }

    /**
     * Mettre à jour une vaccination
     */
    public function update(string $id, array $data): ?Vaccination
    {
        return $this->updateVaccination->execute($id, UpdateVaccinationData::fromArray($data));
    }

    /**
     * Supprimer une vaccination
     */
    public function delete(string $id): bool
    {
        return $this->deleteVaccination->execute($id);
    }
}
