<?php

namespace App\Features\Grossesse;

use App\Models\Grossesse;
use Carbon\Carbon;

class GrossessePresenter
{
    /**
     * Format Grossesse exposé au frontend.
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
            'mamanId' => $grossesse->maman_id,
            'mamanNom' => $grossesse->maman?->name,
            'dateDernieresRegles' => $grossesse->date_debut?->format('Y-m-d'),
            'dateAccouchePrevue' => $grossesse->date_fin_prevue?->format('Y-m-d'),
            'semaineGrossesse' => $weeks,
            'nombreGrossessesPrecedentes' => (int) ($grossesse->nombre_grossesses_precedentes ?? 0),
            'antecedentsMedicaux' => $grossesse->antecedents_medicaux ?? '',
            'statut' => strtoupper($grossesse->statut ?? 'EN_ATTENTE'),
            'professionnelValidateur' => $grossesse->professionnel_validateur,
            'dateValidation' => $grossesse->date_validation?->format('Y-m-d'),
            'trimestre' => (int) ($grossesse->trimestre ?? $trimestre),
            'notes' => $grossesse->notes ?? '',
        ];
    }
}
