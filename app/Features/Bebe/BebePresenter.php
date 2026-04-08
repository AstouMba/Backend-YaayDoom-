<?php

namespace App\Features\Bebe;

use App\Models\Bebe;
use Carbon\Carbon;

class BebePresenter
{
    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(Bebe $bebe): array
    {
        $ageActuel = null;

        if ($bebe->date_naissance) {
            $months = Carbon::parse($bebe->date_naissance)->diffInMonths(Carbon::now());
            $ageActuel = $months . ' mois';
        }

        return [
            'id' => $bebe->id,
            'grossesse_id' => $bebe->grossesse_id,
            'maman_id' => $bebe->maman_id,
            'nom' => $bebe->nom,
            'date_naissance' => optional($bebe->date_naissance)->format('Y-m-d'),
            'sexe' => $bebe->sexe,
            'poids' => $bebe->poids,
            'taille' => $bebe->taille,
            'groupe_sanguin' => $bebe->groupe_sanguin,
            'poids_actuel' => $bebe->poids_actuel ?? $bebe->poids,
            'taille_actuelle' => $bebe->taille_actuelle ?? $bebe->taille,
            'age_actuel' => $ageActuel,
        ];
    }
}
