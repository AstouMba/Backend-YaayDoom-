<?php

namespace App\Features\Consultation;

use App\Models\Consultation;

class ConsultationPresenter
{
    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(Consultation $consultation): array
    {
        return [
            'id' => $consultation->id,
            'maman_id' => $consultation->maman_id,
            'professionnel_id' => $consultation->professionnel_id,
            'type' => $consultation->type,
            'date' => optional($consultation->date)->format('Y-m-d'),
            'heure' => $consultation->heure,
            'tension_arterielle' => $consultation->tension_arterielle,
            'poids' => $consultation->poids,
            'hauteur_uterine' => $consultation->hauteur_uterine,
            'bcf' => $consultation->bcf,
            'notes' => $consultation->notes,
            'semaine_grossesse' => $consultation->semaine_grossesse,
        ];
    }
}
