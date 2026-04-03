<?php

namespace App\Features\Bebe;

use Illuminate\Support\Facades\Validator;

class BebeValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        $validated = Validator::make($data, [
            'maman_id' => 'required|exists:users,id',
            'grossesse_id' => 'required|exists:grossesses,id',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|string|in:M,F',
            'poids' => 'sometimes|numeric',
            'poids_actuel' => 'sometimes|nullable|numeric',
            'taille' => 'sometimes|numeric',
            'taille_actuelle' => 'sometimes|nullable|numeric',
            'groupe_sanguin' => 'sometimes|nullable|string|max:10',
            'notes' => 'sometimes|string',
        ])->validate();

        $validated['poids_actuel'] = $validated['poids_actuel'] ?? $validated['poids'] ?? null;
        $validated['taille_actuelle'] = $validated['taille_actuelle'] ?? $validated['taille'] ?? null;

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        $validated = Validator::make($data, [
            'maman_id' => 'sometimes|exists:users,id',
            'grossesse_id' => 'sometimes|nullable|exists:grossesses,id',
            'nom' => 'sometimes|string|max:255',
            'date_naissance' => 'sometimes|date',
            'sexe' => 'sometimes|string|in:M,F',
            'poids' => 'sometimes|numeric',
            'poids_actuel' => 'sometimes|nullable|numeric',
            'taille' => 'sometimes|numeric',
            'taille_actuelle' => 'sometimes|nullable|numeric',
            'groupe_sanguin' => 'sometimes|nullable|string|max:10',
            'notes' => 'sometimes|string',
        ])->validate();

        if (array_key_exists('poids', $validated) && !array_key_exists('poids_actuel', $validated)) {
            $validated['poids_actuel'] = $validated['poids'];
        }

        if (array_key_exists('taille', $validated) && !array_key_exists('taille_actuelle', $validated)) {
            $validated['taille_actuelle'] = $validated['taille'];
        }

        return $validated;
    }
}
