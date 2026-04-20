<?php

namespace App\Features\RendezVous;

use App\Models\RendezVous;

class RendezVousPresenter
{
    /**
     * Format RendezVous exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(RendezVous $rendezVous): array
    {
        return [
            'id' => $rendezVous->id,
            'type' => $rendezVous->type,
            'date' => $rendezVous->date?->format('Y-m-d'),
            'heure' => $rendezVous->heure,
            'professionnel' => $rendezVous->professionnel?->name,
            'lieu' => $rendezVous->lieu,
            'statut' => $rendezVous->statut,
            'notes' => $rendezVous->notes ?? '',
        ];
    }
}
