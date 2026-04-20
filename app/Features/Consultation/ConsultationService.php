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
use App\Models\User;
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
    public function getAll(?User $user = null): Collection
    {
        return $this->listConsultations->execute($user);
    }

    /**
     * Récupérer une consultation par ID
     */
    public function get(string $id, ?User $user = null): ?Consultation
    {
        return $this->getConsultation->execute($id, $user);
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
    public function update(string $id, array $data, ?User $user = null): ?Consultation
    {
        return $this->updateConsultation->execute($id, UpdateConsultationData::fromArray($data), $user);
    }

    /**
     * Supprimer une consultation
     */
    public function delete(string $id, ?User $user = null): bool
    {
        return $this->deleteConsultation->execute($id, $user);
    }
}
