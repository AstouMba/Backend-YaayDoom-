<?php

namespace App\Application\Consultation;

use App\Application\Consultation\DTO\UpdateConsultationData;
use App\Models\Consultation;
use App\Models\User;
use App\Services\Service;

class UpdateConsultation extends Service
{
    public function execute(string $id, UpdateConsultationData $data, ?User $user = null): Consultation
    {
        $query = Consultation::query();

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $consultation = $query->find($id);

        if (!$consultation) {
            $this->notFound('consultation_not_found');
        }

        $consultation->update(array_filter([
            'maman_id' => $data->mamanId,
            'professionnel_id' => $data->professionnelId,
            'date' => $data->date,
            'heure' => $data->heure,
            'type' => $data->type,
            'tension_arterielle' => $data->tensionArterielle,
            'poids' => $data->poids,
            'hauteur_uterine' => $data->hauteurUterine,
            'bcf' => $data->bcf,
            'semaine_grossesse' => $data->semaineGrossesse,
            'notes' => $data->notes,
        ], static fn (mixed $value): bool => $value !== null));

        return $consultation;
    }
}
