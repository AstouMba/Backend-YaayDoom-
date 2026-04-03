<?php

namespace App\Features\Consultation;

use Illuminate\Support\Facades\Validator;

class ConsultationValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        return Validator::make($data, [
            'maman_id' => 'required|exists:users,id',
            'professionnel_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'heure' => 'required',
            'type' => 'required|string',
            'tension_arterielle' => 'sometimes|nullable|string|max:20',
            'poids' => 'sometimes|nullable|numeric',
            'hauteur_uterine' => 'sometimes|nullable|numeric',
            'bcf' => 'sometimes|nullable|string|max:20',
            'semaine_grossesse' => 'sometimes|nullable|integer|min:0',
            'notes' => 'sometimes|string',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        return Validator::make($data, [
            'maman_id' => 'sometimes|exists:users,id',
            'professionnel_id' => 'sometimes|exists:users,id',
            'date' => 'sometimes|date',
            'heure' => 'sometimes',
            'type' => 'sometimes|string',
            'tension_arterielle' => 'sometimes|nullable|string|max:20',
            'poids' => 'sometimes|nullable|numeric',
            'hauteur_uterine' => 'sometimes|nullable|numeric',
            'bcf' => 'sometimes|nullable|string|max:20',
            'semaine_grossesse' => 'sometimes|nullable|integer|min:0',
            'notes' => 'sometimes|string',
        ])->validate();
    }
}
