<?php

namespace App\Features\Carte;

use Illuminate\Support\Facades\Validator;

class CarteValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        return Validator::make($data, [
            'maman_id' => 'required|exists:users,id',
            'numero_carte' => 'required|string|unique:cartes,numero_carte',
            'date_emission' => 'required|date',
            'date_expiration' => 'sometimes|date',
            'statut' => 'sometimes|string|in:active,inactive,expiree',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data, string $carteId): array
    {
        return Validator::make($data, [
            'maman_id' => 'sometimes|exists:users,id',
            'numero_carte' => 'sometimes|string|unique:cartes,numero_carte,' . $carteId,
            'date_emission' => 'sometimes|date',
            'date_expiration' => 'sometimes|date',
            'statut' => 'sometimes|string|in:active,inactive,expiree',
        ])->validate();
    }
}
