<?php

namespace App\Features\Grossesse;

use App\Models\Grossesse;
use Carbon\Carbon;

class GrossessePresenter
{
    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(Grossesse $grossesse): array
    {
        $weeks = 0;

        if ($grossesse->date_debut) {
            $weeks = (int) max(0, Carbon::parse($grossesse->date_debut)->diffInWeeks(Carbon::now()));
        }

        $trimestre = $weeks > 0 ? (int) min(3, max(1, (int) ceil($weeks / 13))) : 1;

        return [
            'id' => $grossesse->id,
            'maman_id' => $grossesse->maman_id,
            'maman_nom' => $grossesse->maman?->name,
            'date_debut' => optional($grossesse->date_debut)->format('Y-m-d'),
            'date_fin_prevue' => optional($grossesse->date_fin_prevue)->format('Y-m-d'),
            'semaine_grossesse' => $weeks,
            'nombre_grossesses_precedentes' => (int) ($grossesse->nombre_grossesses_precedentes ?? 0),
            'antecedents_medicaux' => $grossesse->antecedents_medicaux ?? '',
            'statut' => $grossesse->statut,
            'professionnel_validateur' => $grossesse->professionnel_validateur,
            'date_validation' => optional($grossesse->date_validation)->format('Y-m-d'),
            'trimestre' => (int) ($grossesse->trimestre ?? $trimestre),
            'notes' => $grossesse->notes ?? '',
        ];
    }
}
