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
        // Normaliser les noms de champs
        $data['maman_id'] = $data['maman_id'] ?? $data['mamanId'] ?? null;
        $data['grossesse_id'] = $data['grossesse_id'] ?? $data['grossesseId'] ?? null;
        $data['nom'] = $data['nom'] ?? null;
        $data['date_naissance'] = $data['date_naissance'] ?? $data['dateNaissance'] ?? null;
        $data['sexe'] = $data['sexe'] ?? $data['sexe'] ?? null;
        $data['poids'] = $data['poids'] ?? $data['poids'] ?? ($data['poidsNaissance'] ?? null);
        $data['poids_actuel'] = $data['poids_actuel'] ?? $data['poidsActuel'] ?? null;
        $data['taille'] = $data['taille'] ?? $data['taille'] ?? ($data['tailleNaissance'] ?? null);
        $data['taille_actuelle'] = $data['taille_actuelle'] ?? $data['tailleActuelle'] ?? null;
        $data['groupe_sanguin'] = $data['groupe_sanguin'] ?? $data['groupeSanguin'] ?? null;
        $data['notes'] = $data['notes'] ?? null;

        return Validator::make($data, [
            'maman_id' => 'required|exists:users,id',
            'grossesse_id' => 'sometimes|nullable|exists:grossesses,id',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|string|in:M,F,m,f,Masculin,Feminin,masculin,feminin,Garcon,Fille,garcon,fille',
            'poids' => 'sometimes|nullable|numeric',
            'poids_actuel' => 'sometimes|nullable|numeric',
            'taille' => 'sometimes|nullable|numeric',
            'taille_actuelle' => 'sometimes|nullable|numeric',
            'groupe_sanguin' => 'sometimes|nullable|string|max:10',
            'notes' => 'sometimes|nullable|string',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        // Normaliser les noms de champs
        $data['maman_id'] = $data['maman_id'] ?? $data['mamanId'] ?? null;
        $data['grossesse_id'] = $data['grossesse_id'] ?? $data['grossesseId'] ?? null;
        $data['poids'] = $data['poids'] ?? $data['poids'] ?? ($data['poidsActuel'] ?? null);
        $data['poids_actuel'] = $data['poids_actuel'] ?? $data['poidsActuel'] ?? null;
        $data['taille'] = $data['taille'] ?? $data['taille'] ?? ($data['tailleActuelle'] ?? null);
        $data['taille_actuelle'] = $data['taille_actuelle'] ?? $data['tailleActuelle'] ?? null;
        $data['groupe_sanguin'] = $data['groupe_sanguin'] ?? $data['groupeSanguin'] ?? null;

        $validated = Validator::make($data, [
            'maman_id' => 'sometimes|exists:users,id',
            'grossesse_id' => 'sometimes|nullable|exists:grossesses,id',
            'nom' => 'sometimes|string|max:255',
            'date_naissance' => 'sometimes|date',
            'sexe' => 'sometimes|string|in:M,F,m,f,Masculin,Feminin,masculin,feminin,Garcon,Fille,garcon,fille',
            'poids' => 'sometimes|nullable|numeric',
            'poids_actuel' => 'sometimes|nullable|numeric',
            'taille' => 'sometimes|nullable|numeric',
            'taille_actuelle' => 'sometimes|nullable|numeric',
            'groupe_sanguin' => 'sometimes|nullable|string|max:10',
            'notes' => 'sometimes|nullable|string',
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
