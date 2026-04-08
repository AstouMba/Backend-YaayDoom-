<?php

namespace App\Features\Vaccination;

use App\Models\Vaccination;

class VaccinationPresenter
{
    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(Vaccination $vaccination): array
    {
        $age = null;

        if ($vaccination->bebe?->date_naissance && $vaccination->date_vaccination && $vaccination->bebe->date_naissance->equalTo($vaccination->date_vaccination)) {
            $age = 'À la naissance';
        }

        return [
            'id' => $vaccination->id,
            'bebe_id' => $vaccination->bebe_id,
            'nom_vaccin' => $vaccination->nom_vaccin,
            'age' => $vaccination->age ?? $age,
            'date_vaccination' => optional($vaccination->date_vaccination)->format('Y-m-d'),
            'prochaine_dose' => optional($vaccination->prochaine_dose)->format('Y-m-d'),
            'notes' => $vaccination->notes,
            'professionnel_id' => $vaccination->professionnel_id,
        ];
    }
}
