<?php

namespace App\Features\User;

use Illuminate\Support\Facades\Validator;

class UserValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        $validated = Validator::make($data, [
            'nom' => 'required_without:name|string|max:255',
            'name' => 'required_without:nom|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:30|unique:users,phone',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|string|in:user,admin,maman,professionnel',
            'status' => 'sometimes|string|in:actif,inactif',
            'statut' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:255',
            'centre_de_sante' => 'nullable|string|max:255',
            'rejection_reason' => 'nullable|string|max:500',
        ])->validate();

        $validated['name'] = $validated['name'] ?? $validated['nom'] ?? null;
        $validated['phone'] = $validated['phone'] ?? $validated['telephone'] ?? null;
        $validated['status'] = $validated['status'] ?? $validated['statut'] ?? null;

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data, string $userId): array
    {
        $validated = Validator::make($data, [
            'nom' => 'sometimes|string|max:255',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $userId,
            'telephone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $userId,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $userId,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|in:user,admin,maman,professionnel',
            'status' => 'sometimes|string|in:actif,inactif',
            'statut' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'sometimes|nullable|string|max:255',
            'matricule' => 'sometimes|nullable|string|max:255',
            'centre_de_sante' => 'sometimes|nullable|string|max:255',
            'rejection_reason' => 'sometimes|nullable|string|max:500',
        ])->validate();

        $validated['name'] = $validated['name'] ?? $validated['nom'] ?? null;
        $validated['phone'] = $validated['phone'] ?? $validated['telephone'] ?? null;
        $validated['status'] = $validated['status'] ?? $validated['statut'] ?? null;

        return $validated;
    }
}
