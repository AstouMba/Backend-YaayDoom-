<?php

namespace App\Features\Vaccination;

use App\Models\Vaccination;
use Illuminate\Database\Eloquent\Collection;

class VaccinationService
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
    public function get(int $id): ?Vaccination
    {
        return Vaccination::find($id);
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
            return null;
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
            return false;
        }

        return $vaccination->delete();
    }
}
