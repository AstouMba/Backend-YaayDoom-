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
        return Validator::make($data, [
            'bebe_id' => 'required|exists:bebes,id',
            'nom_vaccin' => 'required|string',
            'age' => 'sometimes|nullable|string|max:100',
            'date_vaccination' => 'required|date',
            'prochaine_dose' => 'sometimes|nullable|date',
            'notes' => 'sometimes|string',
            'professionnel_id' => 'sometimes|exists:users,id',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        return Validator::make($data, [
            'bebe_id' => 'sometimes|exists:bebes,id',
            'nom_vaccin' => 'sometimes|string',
            'age' => 'sometimes|nullable|string|max:100',
            'date_vaccination' => 'sometimes|date',
            'prochaine_dose' => 'sometimes|nullable|date',
            'notes' => 'sometimes|string',
            'professionnel_id' => 'sometimes|exists:users,id',
        ])->validate();
    }
}
