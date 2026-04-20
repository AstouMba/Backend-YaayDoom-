<?php

namespace App\Features\Bebe;

use App\Models\Bebe;
use Carbon\Carbon;

class BebePresenter
{
    /**
     * Format Bebe exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(Bebe $bebe): array
    {
        $ageActuel = null;
        $sexeFormat = match ($bebe->sexe) {
            'M', 'm', 'masculin', 'Masculin', 'Garcon', 'garcon' => 'Masculin',
            'F', 'f', 'feminin', 'Feminin', 'Fille', 'fille' => 'Féminin',
            default => 'Inconnu',
        };

        if ($bebe->date_naissance) {
            $months = Carbon::parse($bebe->date_naissance)->diffInMonths(Carbon::now());
            $ageActuel = $months . ' mois';
        }

        return [
            'id' => $bebe->id,
            'grossesseId' => $bebe->grossesse_id,
            'mamanId' => $bebe->maman_id,
            'nom' => $bebe->nom,
            'dateNaissance' => $bebe->date_naissance?->format('Y-m-d'),
            'sexe' => $sexeFormat,
            'poidsNaissance' => (float) $bebe->poids,
            'tailleNaissance' => (float) $bebe->taille,
            'groupeSanguin' => $bebe->groupe_sanguin,
            'ageActuel' => $ageActuel,
            'poidsActuel' => (float) ($bebe->poids_actuel ?? $bebe->poids),
            'tailleActuelle' => (float) ($bebe->taille_actuelle ?? $bebe->taille),
            'mamanNom' => $bebe->maman?->name,
        ];
    }
}
