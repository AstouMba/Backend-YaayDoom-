<?php

namespace App\Features\Professionnel;

use Illuminate\Support\Facades\Validator;

class ProfessionnelValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'required|string|max:255',
            'matricule' => 'required|string|max:255',
            'centre_de_sante' => 'required|string|max:255',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data, string $professionnelId): array
    {
        return Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $professionnelId,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $professionnelId,
            'password' => 'sometimes|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'sometimes|nullable|string|max:255',
            'matricule' => 'sometimes|nullable|string|max:255',
            'centre_de_sante' => 'sometimes|nullable|string|max:255',
        ])->validate();
    }
}
