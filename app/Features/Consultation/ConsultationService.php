<?php

namespace App\Features\Consultation;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Collection;

class ConsultationService
{
    /**
     * Récupérer toutes les consultations
     */
    public function getAll(): Collection
    {
        return Consultation::all();
    }

    /**
     * Récupérer une consultation par ID
     */
    public function get(int $id): ?Consultation
    {
        return Consultation::find($id);
    }

    /**
     * Créer une consultation
     */
    public function create(array $data): Consultation
    {
        return Consultation::create($data);
    }

    /**
     * Mettre à jour une consultation
     */
    public function update(string $id, array $data): ?Consultation
    {
        $consultation = Consultation::find($id);
        
        if (!$consultation) {
            return null;
        }

        $consultation->update($data);
        
        return $consultation;
    }

    /**
     * Supprimer une consultation
     */
    public function delete(string $id): bool
    {
        $consultation = Consultation::find($id);
        
        if (!$consultation) {
            return false;
        }

        return $consultation->delete();
    }
}
