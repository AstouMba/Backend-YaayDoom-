<?php

namespace App\Features\RendezVous;

use App\Models\RendezVous;

class RendezVousPresenter
{
    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(RendezVous $rendezVous): array
    {
        return [
            'id' => $rendezVous->id,
            'maman_id' => $rendezVous->maman_id,
            'grossesse_id' => $rendezVous->grossesse_id,
            'type' => $rendezVous->type,
            'motif' => $rendezVous->motif,
            'date' => optional($rendezVous->date)->format('Y-m-d'),
            'heure' => $rendezVous->heure,
            'professionnel_id' => $rendezVous->professionnel_id,
            'professionnel' => $rendezVous->professionnel?->name,
            'lieu' => $rendezVous->lieu,
            'statut' => $rendezVous->statut,
            'notes' => $rendezVous->notes,
        ];
    }
}
