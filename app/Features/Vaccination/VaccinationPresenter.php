<?php

namespace App\Features\Vaccination;

use App\Models\Vaccination;

class VaccinationPresenter
{
    /**
     * Format Vaccin exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(Vaccination $vaccination): array
    {
        $statut = $vaccination->date_vaccination ? 'completed' : 'upcoming';

        return [
            'id' => $vaccination->id,
            'bebeId' => $vaccination->bebe_id,
            'nom' => $vaccination->nom_vaccin,
            'age' => $vaccination->age,
            'datePrevu' => $vaccination->date_vaccination?->format('Y-m-d'),
            'dateAdministre' => $vaccination->date_vaccination?->format('Y-m-d'),
            'statut' => $statut,
            'professionnel' => $vaccination->professionnel?->name,
            'notes' => $vaccination->notes ?? '',
            'bebeNom' => $vaccination->bebe?->nom,
        ];
    }
}
