<?php

namespace App\Features\Bebe;

use App\Models\Bebe;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class BebeService extends Service
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
    public function get(string $id): ?Bebe
    {
        $bebe = Bebe::find($id);

        if (!$bebe) {
            $this->notFound('bebe_not_found');
        }

        return $bebe;
    }

    /**
     * Créer un bébé
     */
    public function create(array $data): Bebe
    {
        $data['poids_actuel'] = $data['poids_actuel'] ?? $data['poids'] ?? null;
        $data['taille_actuelle'] = $data['taille_actuelle'] ?? $data['taille'] ?? null;

        return Bebe::create($data);
    }

    /**
     * Mettre à jour un bébé
     */
    public function update(string $id, array $data): ?Bebe
    {
        $bebe = Bebe::find($id);
        
        if (!$bebe) {
            $this->notFound('bebe_not_found');
        }

        if (array_key_exists('poids', $data) && !array_key_exists('poids_actuel', $data)) {
            $data['poids_actuel'] = $data['poids'];
        }

        if (array_key_exists('taille', $data) && !array_key_exists('taille_actuelle', $data)) {
            $data['taille_actuelle'] = $data['taille'];
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
            $this->notFound('bebe_not_found');
        }

        return $bebe->delete();
    }
}
