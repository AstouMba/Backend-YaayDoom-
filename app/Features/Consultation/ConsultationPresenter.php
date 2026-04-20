<?php

namespace App\Features\Consultation;

use App\Models\Consultation;

class ConsultationPresenter
{
    /**
     * Format Consultation exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(Consultation $consultation): array
    {
        return [
            'id' => $consultation->id,
            'patientName' => $consultation->maman?->name,
            'patientId' => $consultation->maman_id,
            'type' => $consultation->type,
            'date' => $consultation->date?->format('Y-m-d'),
            'tensionArterielle' => $consultation->tension_arterielle,
            'poids' => $consultation->poids,
            'notes' => $consultation->notes ?? '',
            'semaineGrossesse' => $consultation->semaine_grossesse,
            'mamanId' => $consultation->maman_id,
            'professionnelId' => $consultation->professionnel_id,
            'heure' => $consultation->heure,
        ];
    }
}
