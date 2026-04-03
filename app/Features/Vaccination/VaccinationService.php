<?php

namespace App\Features\Vaccination;

use App\Models\Vaccination;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class VaccinationService extends Service
{
    /**
     * Récupérer toutes les vaccinations
     */
    public function getAll(): Collection
    {
        return Vaccination::all();
    }

    /**
     * Récupérer une vaccination par ID
     */
    public function get(string $id): ?Vaccination
    {
        $vaccination = Vaccination::find($id);

        if (!$vaccination) {
            $this->notFound('vaccination_not_found');
        }

        return $vaccination;
    }

    /**
     * Créer une vaccination
     */
    public function create(array $data): Vaccination
    {
        return Vaccination::create($data);
    }

    /**
     * Mettre à jour une vaccination
     */
    public function update(string $id, array $data): ?Vaccination
    {
        $vaccination = Vaccination::find($id);
        
        if (!$vaccination) {
            $this->notFound('vaccination_not_found');
        }

        $vaccination->update($data);
        
        return $vaccination;
    }

    /**
     * Supprimer une vaccination
     */
    public function delete(string $id): bool
    {
        $vaccination = Vaccination::find($id);
        
        if (!$vaccination) {
            $this->notFound('vaccination_not_found');
        }

        return $vaccination->delete();
    }
}
