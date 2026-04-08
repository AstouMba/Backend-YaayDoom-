<?php

namespace App\Application\Consultation;

use App\Application\Consultation\DTO\UpdateConsultationData;
use App\Models\Consultation;
use App\Services\Service;

class UpdateConsultation extends Service
{
    public function execute(string $id, UpdateConsultationData $data): Consultation
    {
        $consultation = Consultation::find($id);

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
