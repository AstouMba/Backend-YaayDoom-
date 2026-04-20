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
        // Normaliser les noms de champs
        $data['maman_id'] = $data['maman_id'] ?? $data['mamanId'] ?? $data['patientId'] ?? null;
        $data['professionnel_id'] = $data['professionnel_id'] ?? $data['professionnelId'] ?? null;
        $data['type'] = $data['type'] ?? null;
        $data['date'] = $data['date'] ?? null;
        $data['heure'] = $data['heure'] ?? null;
        $data['tension_arterielle'] = $data['tension_arterielle'] ?? $data['tensionArterielle'] ?? null;
        $data['poids'] = $data['poids'] ?? null;
        $data['hauteur_uterine'] = $data['hauteur_uterine'] ?? $data['hauteurUterine'] ?? null;
        $data['bcf'] = $data['bcf'] ?? null;
        $data['semaine_grossesse'] = $data['semaine_grossesse'] ?? $data['semaineGrossesse'] ?? null;
        $data['notes'] = $data['notes'] ?? null;

        return Validator::make($data, [
            'maman_id' => 'required|exists:users,id',
            'professionnel_id' => 'sometimes|nullable|exists:users,id',
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'heure' => 'sometimes|nullable|string|max:10',
            'tension_arterielle' => 'sometimes|nullable|string|max:20',
            'poids' => 'sometimes|nullable|numeric',
            'hauteur_uterine' => 'sometimes|nullable|numeric',
            'bcf' => 'sometimes|nullable|string|max:20',
            'semaine_grossesse' => 'sometimes|nullable|integer|min:0',
            'notes' => 'sometimes|nullable|string',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        // Normaliser les noms de champs
        $data['maman_id'] = $data['maman_id'] ?? $data['mamanId'] ?? $data['patientId'] ?? null;
        $data['professionnel_id'] = $data['professionnel_id'] ?? $data['professionnelId'] ?? null;
        $data['tension_arterielle'] = $data['tension_arterielle'] ?? $data['tensionArterielle'] ?? null;
        $data['semaine_grossesse'] = $data['semaine_grossesse'] ?? $data['semaineGrossesse'] ?? null;

        return Validator::make($data, [
            'maman_id' => 'sometimes|exists:users,id',
            'professionnel_id' => 'sometimes|nullable|exists:users,id',
            'type' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'heure' => 'sometimes|nullable|string|max:10',
            'tension_arterielle' => 'sometimes|nullable|string|max:20',
            'poids' => 'sometimes|nullable|numeric',
            'hauteur_uterine' => 'sometimes|nullable|numeric',
            'bcf' => 'sometimes|nullable|string|max:20',
            'semaine_grossesse' => 'sometimes|nullable|integer|min:0',
            'notes' => 'sometimes|nullable|string',
        ])->validate();
    }
}
