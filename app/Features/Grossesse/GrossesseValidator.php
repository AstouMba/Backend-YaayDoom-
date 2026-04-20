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
        // Normaliser les noms de champs camelCase vers snake_case
        $data['date_debut'] = $data['date_debut'] ?? $data['dateDernieresRegles'] ?? null;
        $data['date_fin_prevue'] = $data['date_fin_prevue'] ?? $data['dateAccouchePrevue'] ?? null;
        $data['nombre_grossesses_precedentes'] = $data['nombre_grossesses_precedentes'] ?? $data['nombreGrossessesPrecedentes'] ?? null;
        $data['antecedents_medicaux'] = $data['antecedents_medicaux'] ?? $data['antecedentsMedicaux'] ?? null;
        $data['professionnel_validateur'] = $data['professionnel_validateur'] ?? $data['professionnelValidateur'] ?? null;
        $data['date_validation'] = $data['date_validation'] ?? $data['dateValidation'] ?? null;
        $data['trimestre'] = $data['trimestre'] ?? null;
        $data['statut'] = $data['statut'] ?? 'EN_ATTENTE';
        $data['notes'] = $data['notes'] ?? null;

        return Validator::make($data, [
            'date_debut' => 'required|date',
            'date_fin_prevue' => 'sometimes|nullable|date',
            'nombre_grossesses_precedentes' => 'sometimes|nullable|integer|min:0',
            'antecedents_medicaux' => 'sometimes|nullable|string',
            'professionnel_validateur' => 'sometimes|nullable|exists:users,id',
            'date_validation' => 'sometimes|nullable|date',
            'trimestre' => 'sometimes|nullable|integer|min:1|max:3',
            'statut' => 'sometimes|string|in:EN_ATTENTE,VALIDEE,TERMINEE,ANNULEE,en_attente,validee,terminee,annulee',
            'notes' => 'sometimes|nullable|string',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        // Normaliser les noms de champs
        $data['date_debut'] = $data['date_debut'] ?? $data['dateDernieresRegles'] ?? null;
        $data['date_fin_prevue'] = $data['date_fin_prevue'] ?? $data['dateAccouchePrevue'] ?? null;
        $data['nombre_grossesses_precedentes'] = $data['nombre_grossesses_precedentes'] ?? $data['nombreGrossessesPrecedentes'] ?? null;
        $data['antecedents_medicaux'] = $data['antecedents_medicaux'] ?? $data['antecedentsMedicaux'] ?? null;
        $data['professionnel_validateur'] = $data['professionnel_validateur'] ?? $data['professionnelValidateur'] ?? null;
        $data['date_validation'] = $data['date_validation'] ?? $data['dateValidation'] ?? null;
        $data['trimestre'] = $data['trimestre'] ?? null;

        return Validator::make($data, [
            'maman_id' => 'sometimes|exists:users,id',
            'date_debut' => 'sometimes|date',
            'date_fin_prevue' => 'sometimes|nullable|date',
            'nombre_grossesses_precedentes' => 'sometimes|nullable|integer|min:0',
            'antecedents_medicaux' => 'sometimes|nullable|string',
            'professionnel_validateur' => 'sometimes|nullable|exists:users,id',
            'date_validation' => 'sometimes|nullable|date',
            'trimestre' => 'sometimes|nullable|integer|min:1|max:3',
            'statut' => 'sometimes|string',
            'notes' => 'sometimes|nullable|string',
        ])->validate();
    }
}
