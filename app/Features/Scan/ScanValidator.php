<?php

namespace App\Features\Scan;

use Illuminate\Support\Facades\Validator;

class ScanValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        return Validator::make($data, [
            'bebe_id' => 'required|exists:bebes,id',
            'type_scan' => 'required|string',
            'date_scan' => 'required|date',
            'resultat' => 'sometimes|string',
            'notes' => 'sometimes|string',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data): array
    {
        return Validator::make($data, [
            'bebe_id' => 'sometimes|exists:bebes,id',
            'type_scan' => 'sometimes|string',
            'date_scan' => 'sometimes|date',
            'resultat' => 'sometimes|string',
            'notes' => 'sometimes|string',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function resolve(array $data): array
    {
        return Validator::make($data, [
            'qr_code' => 'required|string|min:1|max:255',
        ])->validate();
    }
}
