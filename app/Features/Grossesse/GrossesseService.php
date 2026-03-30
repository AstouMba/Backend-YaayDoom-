<?php

namespace App\Features\Grossesse;

use App\Models\Grossesse;
use Illuminate\Database\Eloquent\Collection;

class GrossesseService
{
    /**
     * Récupérer toutes les grossesses
     */
    public function getAll(): Collection
    {
        return Grossesse::all();
    }

    /**
     * Récupérer une grossesse par ID
     */
    public function get(int $id): ?Grossesse
    {
        return Grossesse::find($id);
    }

    /**
     * Créer une grossesse
     */
    public function create(array $data): Grossesse
    {
        $data['statut'] = 'en_attente';

        return Grossesse::create($data);
    }

    /**
     * Mettre à jour une grossesse
     */
    public function update(string $id, array $data): ?Grossesse
    {
        $grossesse = Grossesse::find($id);
        
        if (!$grossesse) {
            return null;
        }

        $grossesse->update($data);
        
        return $grossesse;
    }

    /**
     * Supprimer une grossesse
     */
    public function delete(string $id): bool
    {
        $grossesse = Grossesse::find($id);
        
        if (!$grossesse) {
            return false;
        }

        return $grossesse->delete();
    }
}
