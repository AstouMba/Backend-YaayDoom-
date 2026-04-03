<?php

namespace App\Features\RendezVous;

use App\Models\Grossesse;
use App\Models\RendezVous;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class RendezVousService extends Service
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
    public function get(string $id): ?RendezVous
    {
        $rendezVous = RendezVous::find($id);

        if (!$rendezVous) {
            $this->notFound('rendez_vous_not_found');
        }

        return $rendezVous;
    }

    /**
     * Créer un rendez-vous
     */
    public function create(array $data): RendezVous
    {
        $grossesse = Grossesse::find($data['grossesse_id']);
        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
        }

        $data['maman_id'] = $grossesse->maman_id;
        $data['statut'] = $data['statut'] ?? 'prévu';

        return RendezVous::create($data);
    }

    /**
     * Mettre à jour un rendez-vous
     */
    public function update(string $id, array $data): ?RendezVous
    {
        $rendezVous = RendezVous::find($id);
        
        if (!$rendezVous) {
            $this->notFound('rendez_vous_not_found');
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
            $this->notFound('rendez_vous_not_found');
        }

        return $rendezVous->delete();
    }
}
