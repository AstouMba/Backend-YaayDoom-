<?php

namespace App\Features\RendezVous;

use App\Models\RendezVous;
use Illuminate\Database\Eloquent\Collection;

class RendezVousService
{
    /**
     * Récupérer tous les rendez-vous
     */
    public function getAll(): Collection
    {
        return RendezVous::all();
    }

    /**
     * Récupérer un rendez-vous par ID
     */
    public function get(int $id): ?RendezVous
    {
        return RendezVous::find($id);
    }

    /**
     * Créer un rendez-vous
     */
    public function create(array $data): RendezVous
    {
        return RendezVous::create($data);
    }

    /**
     * Mettre à jour un rendez-vous
     */
    public function update(string $id, array $data): ?RendezVous
    {
        $rendezVous = RendezVous::find($id);
        
        if (!$rendezVous) {
            return null;
        }

        $rendezVous->update($data);
        
        return $rendezVous;
    }

    /**
     * Supprimer un rendez-vous
     */
    public function delete(string $id): bool
    {
        $rendezVous = RendezVous::find($id);
        
        if (!$rendezVous) {
            return false;
        }

        return $rendezVous->delete();
    }
}
