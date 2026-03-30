<?php

namespace App\Features\Carte;

use App\Models\Carte;
use Illuminate\Database\Eloquent\Collection;

class CarteService
{
    /**
     * Récupérer toutes les cartes
     */
    public function getAll(): Collection
    {
        return Carte::all();
    }

    /**
     * Récupérer une carte par ID
     */
    public function get(int $id): ?Carte
    {
        return Carte::find($id);
    }

    /**
     * Créer une carte
     */
    public function create(array $data): Carte
    {
        return Carte::create($data);
    }

    /**
     * Mettre à jour une carte
     */
    public function update(string $id, array $data): ?Carte
    {
        $carte = Carte::find($id);
        
        if (!$carte) {
            return null;
        }

        $carte->update($data);
        
        return $carte;
    }

    /**
     * Supprimer une carte
     */
    public function delete(string $id): bool
    {
        $carte = Carte::find($id);
        
        if (!$carte) {
            return false;
        }

        return $carte->delete();
    }
}
