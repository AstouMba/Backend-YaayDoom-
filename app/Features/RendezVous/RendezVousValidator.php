<?php

namespace App\Features\RendezVous;

use Illuminate\Support\Facades\Validator;

class RendezVousValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        return Validator::make($data, [
            'grossesse_id' => 'required|exists:grossesses,id',
            'type' => 'required|string|max:255',
            'motif' => 'required|string|max:255',
            'date' => 'required|date',
            'heure' => 'required',
            'professionnel_id' => 'required|exists:users,id',
            'lieu' => 'required|string|max:255',
            'notes' => 'sometimes|nullable|string',
            'statut' => 'sometimes|string|in:prévu,confirme,annule,termine',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        return Validator::make($data, [
            'grossesse_id' => 'sometimes|exists:grossesses,id',
            'professionnel_id' => 'sometimes|exists:users,id',
            'date' => 'sometimes|date',
            'heure' => 'sometimes',
            'type' => 'sometimes|string|max:255',
            'motif' => 'sometimes|string',
            'lieu' => 'sometimes|string|max:255',
            'notes' => 'sometimes|nullable|string',
            'statut' => 'sometimes|string|in:prévu,confirme,annule,termine',
        ])->validate();
    }
}
