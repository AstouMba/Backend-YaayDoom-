<?php

namespace App\Features\Grossesse;

use Illuminate\Support\Facades\Validator;

class GrossesseValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        return Validator::make($data, [
            'date_debut' => 'required|date',
            'date_fin_prevue' => 'sometimes|date',
            'nombre_grossesses_precedentes' => 'sometimes|integer|min:0',
            'antecedents_medicaux' => 'sometimes|nullable|string',
            'professionnel_validateur' => 'sometimes|nullable|exists:users,id',
            'date_validation' => 'sometimes|nullable|date',
            'trimestre' => 'sometimes|integer|min:1|max:3',
            'statut' => 'sometimes|string|in:en_attente,validee,en_cours,terminee,annulee',
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
            'date_debut' => 'sometimes|date',
            'date_fin_prevue' => 'sometimes|date',
            'nombre_grossesses_precedentes' => 'sometimes|integer|min:0',
            'antecedents_medicaux' => 'sometimes|nullable|string',
            'professionnel_validateur' => 'sometimes|nullable|exists:users,id',
            'date_validation' => 'sometimes|nullable|date',
            'trimestre' => 'sometimes|integer|min:1|max:3',
            'statut' => 'sometimes|string|in:en_attente,validee,en_cours,terminee,annulee',
            'notes' => 'sometimes|string',
        ])->validate();
    }
}
