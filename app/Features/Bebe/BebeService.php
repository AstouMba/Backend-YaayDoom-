<?php

namespace App\Features\Bebe;

use App\Models\Bebe;
use Illuminate\Database\Eloquent\Collection;

class BebeService
{
    /**
     * Récupérer tous les bébés
     */
    public function getAll(): Collection
    {
        return Bebe::all();
    }

    /**
     * Récupérer un bébé par ID
     */
    public function get(int $id): ?Bebe
    {
        return Bebe::find($id);
    }

    /**
     * Créer un bébé
     */
    public function create(array $data): Bebe
    {
        return Bebe::create($data);
    }

    /**
     * Mettre à jour un bébé
     */
    public function update(string $id, array $data): ?Bebe
    {
        $bebe = Bebe::find($id);
        
        if (!$bebe) {
            return null;
        }

        $bebe->update($data);
        
        return $bebe;
    }

    /**
     * Supprimer un bébé
     */
    public function delete(string $id): bool
    {
        $bebe = Bebe::find($id);
        
        if (!$bebe) {
            return false;
        }

        return $bebe->delete();
    }
}
