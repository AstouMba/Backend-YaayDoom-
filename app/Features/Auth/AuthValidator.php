<?php

namespace App\Features\Auth;

use Illuminate\Support\Facades\Validator;

class AuthValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function register(array $data): array
    {
        $data['role'] = $data['role'] ?? 'maman';

        $validated = Validator::make($data, [
            'fullName' => 'required_without:name|string|max:255',
            'name' => 'required_without:fullName|string|max:255',
            'email' => 'required_if:role,professionnel|nullable|email|unique:users,email',
            'phone' => 'required|string|max:30|unique:users,phone',
            'birthDate' => 'required_if:role,maman|nullable|date',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|string|in:maman,professionnel',
            'specialty' => 'nullable|string|max:255',
            'specialite' => 'nullable|string|max:255',
            'healthCenter' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:255',
            'centre_de_sante' => 'nullable|string|max:255',
        ])->validate();

        $validated['name'] = $validated['name'] ?? $validated['fullName'] ?? null;
        $validated['specialite'] = $validated['specialite'] ?? $validated['specialty'] ?? null;
        $validated['centre_de_sante'] = $validated['centre_de_sante'] ?? $validated['healthCenter'] ?? null;

        if (($validated['role'] ?? 'maman') === 'maman') {
            $validated['email'] = $validated['email'] ?? null;
        }

        if (($validated['role'] ?? 'maman') === 'professionnel') {
            Validator::make($validated, [
                'email' => 'required|email|unique:users,email',
                'specialite' => 'required_without:specialty|string|max:255',
                'specialty' => 'required_without:specialite|string|max:255',
                'matricule' => 'required|string|max:255',
                'centre_de_sante' => 'required_without:healthCenter|string|max:255',
                'healthCenter' => 'nullable|string|max:255',
            ])->validate();
        }

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    public static function login(array $data): array
    {
        return Validator::make($data, [
            'password' => 'required|string',
            'login' => 'nullable|string',
            'loginId' => 'nullable|string',
            'email' => 'nullable|email',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function updateMe(array $data, string $userId): array
    {
        return Validator::make($data, [
            'nom' => 'sometimes|string|max:255',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $userId,
            'telephone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $userId,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $userId,
            'specialite' => 'sometimes|nullable|string|max:255',
            'centre_de_sante' => 'sometimes|nullable|string|max:255',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function changePassword(array $data): array
    {
        return Validator::make($data, [
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function uploadProfessionalDocuments(array $data): array
    {
        return Validator::make($data, [
            'documents' => 'required|array|min:1|max:10',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ])->validate();
    }
}
