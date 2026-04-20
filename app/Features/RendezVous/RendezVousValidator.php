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
        // Normaliser les noms de champs
        $data['maman_id'] = $data['maman_id'] ?? $data['mamanId'] ?? null;
        $data['grossesse_id'] = $data['grossesse_id'] ?? $data['grossesseId'] ?? null;
        $data['professionnel_id'] = $data['professionnel_id'] ?? $data['professionnelId'] ?? null;
        $data['type'] = $data['type'] ?? $data['type'] ?? null;
        $data['motif'] = $data['motif'] ?? $data['motif'] ?? null;
        $data['date'] = $data['date'] ?? null;
        $data['heure'] = $data['heure'] ?? null;
        $data['lieu'] = $data['lieu'] ?? null;
        $data['notes'] = $data['notes'] ?? null;
        $data['statut'] = $data['statut'] ?? 'prévu';

        return Validator::make($data, [
            'maman_id' => 'required|exists:users,id',
            'grossesse_id' => 'sometimes|nullable|exists:grossesses,id',
            'type' => 'required|string|max:255',
            'motif' => 'sometimes|nullable|string|max:255',
            'date' => 'required|date',
            'heure' => 'required|string|max:10',
            'professionnel_id' => 'sometimes|nullable|exists:users,id',
            'lieu' => 'sometimes|nullable|string|max:255',
            'notes' => 'sometimes|nullable|string',
            'statut' => 'sometimes|string|in:prévu,confirme,annule,termine,prevu,confirme,annule,termine',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        // Normaliser les noms de champs
        $data['grossesse_id'] = $data['grossesse_id'] ?? $data['grossesseId'] ?? null;
        $data['professionnel_id'] = $data['professionnel_id'] ?? $data['professionnelId'] ?? null;

        return Validator::make($data, [
            'maman_id' => 'sometimes|exists:users,id',
            'grossesse_id' => 'sometimes|nullable|exists:grossesses,id',
            'professionnel_id' => 'sometimes|nullable|exists:users,id',
            'type' => 'sometimes|string|max:255',
            'motif' => 'sometimes|nullable|string',
            'date' => 'sometimes|date',
            'heure' => 'sometimes|string|max:10',
            'lieu' => 'sometimes|nullable|string|max:255',
            'notes' => 'sometimes|nullable|string',
            'statut' => 'sometimes|string',
        ])->validate();
    }
}
