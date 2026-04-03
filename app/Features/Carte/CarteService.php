<?php

namespace App\Features\Carte;

use App\Models\Carte;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class CarteService extends Service
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
    public function get(string $id): ?Carte
    {
        $carte = Carte::find($id);

        if (!$carte) {
            $this->notFound('carte_not_found');
        }

        return $carte;
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
            $this->notFound('carte_not_found');
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
            $this->notFound('carte_not_found');
        }

        return $carte->delete();
    }
}
