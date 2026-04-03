<?php

namespace App\Features\Consultation;

use App\Models\Consultation;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ConsultationService extends Service
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
    public function get(string $id): ?Consultation
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            $this->notFound('consultation_not_found');
        }

        return $consultation;
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
            $this->notFound('consultation_not_found');
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
            $this->notFound('consultation_not_found');
        }

        return $consultation->delete();
    }
}
