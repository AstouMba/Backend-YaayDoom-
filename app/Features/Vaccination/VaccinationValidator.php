<?php

namespace App\Features\Vaccination;

use Illuminate\Support\Facades\Validator;

class VaccinationValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        // Normaliser les noms de champs
        $data['bebe_id'] = $data['bebe_id'] ?? $data['bebeId'] ?? null;
        $data['nom_vaccin'] = $data['nom_vaccin'] ?? $data['nom'] ?? $data['nomVaccin'] ?? null;
        $data['age'] = $data['age'] ?? null;
        $data['date_vaccination'] = $data['date_vaccination'] ?? $data['dateAdministre'] ?? $data['datePrevu'] ?? null;
        $data['prochaine_dose'] = $data['prochaine_dose'] ?? $data['prochainRappel'] ?? null;
        $data['notes'] = $data['notes'] ?? null;
        $data['professionnel_id'] = $data['professionnel_id'] ?? $data['professionnelId'] ?? null;

        return Validator::make($data, [
            'bebe_id' => 'required|exists:bebes,id',
            'nom_vaccin' => 'required|string|max:255',
            'age' => 'sometimes|nullable|string|max:100',
            'date_vaccination' => 'required|date',
            'prochaine_dose' => 'sometimes|nullable|date',
            'notes' => 'sometimes|nullable|string',
            'professionnel_id' => 'sometimes|nullable|exists:users,id',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        // Normaliser les noms de champs
        $data['bebe_id'] = $data['bebe_id'] ?? $data['bebeId'] ?? null;
        $data['nom_vaccin'] = $data['nom_vaccin'] ?? $data['nom'] ?? $data['nomVaccin'] ?? null;
        $data['date_vaccination'] = $data['date_vaccination'] ?? $data['dateAdministre'] ?? $data['datePrevu'] ?? null;
        $data['prochaine_dose'] = $data['prochaine_dose'] ?? $data['prochainRappel'] ?? null;

        return Validator::make($data, [
            'bebe_id' => 'sometimes|exists:bebes,id',
            'nom_vaccin' => 'sometimes|string|max:255',
            'age' => 'sometimes|nullable|string|max:100',
            'date_vaccination' => 'sometimes|date',
            'prochaine_dose' => 'sometimes|nullable|date',
            'notes' => 'sometimes|nullable|string',
            'professionnel_id' => 'sometimes|nullable|exists:users,id',
        ])->validate();
    }
}
