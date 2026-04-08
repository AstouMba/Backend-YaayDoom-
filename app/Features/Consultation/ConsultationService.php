<?php

namespace App\Features\Consultation;

use App\Application\Consultation\CreateConsultation;
use App\Application\Consultation\DeleteConsultation;
use App\Application\Consultation\DTO\CreateConsultationData;
use App\Application\Consultation\DTO\UpdateConsultationData;
use App\Application\Consultation\GetConsultation;
use App\Application\Consultation\ListConsultations;
use App\Application\Consultation\UpdateConsultation;
use App\Models\Consultation;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ConsultationService extends Service
{
    public function __construct(
        private ListConsultations $listConsultations,
        private GetConsultation $getConsultation,
        private CreateConsultation $createConsultation,
        private UpdateConsultation $updateConsultation,
        private DeleteConsultation $deleteConsultation,
    ) {}

    /**
     * Récupérer toutes les consultations
     */
    public function getAll(): Collection
    {
        return $this->listConsultations->execute();
    }

    /**
     * Récupérer une consultation par ID
     */
    public function get(string $id): ?Consultation
    {
        return $this->getConsultation->execute($id);
    }

    /**
     * Créer une consultation
     */
    public function create(array $data): Consultation
    {
        return $this->createConsultation->execute(CreateConsultationData::fromArray($data));
    }

    /**
     * Mettre à jour une consultation
     */
    public function update(string $id, array $data): ?Consultation
    {
        return $this->updateConsultation->execute($id, UpdateConsultationData::fromArray($data));
    }

    /**
     * Supprimer une consultation
     */
    public function delete(string $id): bool
    {
        return $this->deleteConsultation->execute($id);
    }
}
