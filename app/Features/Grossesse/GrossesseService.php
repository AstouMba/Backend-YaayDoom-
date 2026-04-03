<?php

namespace App\Features\Grossesse;

use App\Models\Grossesse;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class GrossesseService extends Service
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
    public function get(string $id): ?Grossesse
    {
        $grossesse = Grossesse::find($id);

        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
        }

        return $grossesse;
    }

    /**
     * Créer une grossesse
     */
    public function create(array $data, ?User $user = null): Grossesse
    {
        if (!$user) {
            $this->unauthorized();
        }

        $data['maman_id'] = $user->id;
        $data['statut'] = 'en_attente';
        $data['nombre_grossesses_precedentes'] = $data['nombre_grossesses_precedentes'] ?? 0;
        $data['trimestre'] = $data['trimestre'] ?? 1;

        return Grossesse::create($data);
    }

    /**
     * Mettre à jour une grossesse
     */
    public function update(string $id, array $data): ?Grossesse
    {
        $grossesse = Grossesse::find($id);
        
        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
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
            $this->notFound('grossesse_not_found');
        }

        return $grossesse->delete();
    }
}
