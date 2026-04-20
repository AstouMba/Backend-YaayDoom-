<?php

namespace App\Features\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function register(array $data): array
    {
        $data['fullName'] = $data['fullName'] ?? $data['name'] ?? $data['full_name'] ?? null;
        $data['name'] = $data['name'] ?? $data['fullName'] ?? $data['full_name'] ?? null;
        $data['phone'] = $data['phone'] ?? $data['telephone'] ?? $data['tel'] ?? null;
        $data['birthDate'] = $data['birthDate'] ?? $data['birth_date'] ?? $data['date_naissance'] ?? null;
        $data['password_confirmation'] = $data['password_confirmation']
            ?? $data['passwordConfirmation']
            ?? $data['confirmPassword']
            ?? null;
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
        foreach (['login', 'loginId', 'identifier', 'identifiant', 'email', 'telephone', 'phone', 'tel'] as $field) {
            if (array_key_exists($field, $data) && is_string($data[$field]) && trim($data[$field]) === '') {
                $data[$field] = null;
            }
        }

        $identifiant = self::firstFilledString(
            $data['identifiant'] ?? null,
            $data['loginId'] ?? null,
            $data['identifier'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['telephone'] ?? null,
            $data['tel'] ?? null
        );

        if ($identifiant === null) {
            throw ValidationException::withMessages([
                'identifiant' => ['L’email ou le numéro de téléphone est requis.'],
            ]);
        }

        $validated = Validator::make([
            'identifiant' => $identifiant,
            'password' => $data['password'] ?? null,
        ], [
            'identifiant' => 'required|string',
            'password' => 'required|string',
        ], [
            'identifiant.required' => "L'email ou le numéro de téléphone est requis.",
            'password.required' => 'Le mot de passe est requis.',
        ])->validate();

        if (! filter_var($validated['identifiant'], FILTER_VALIDATE_EMAIL)) {
            $validated['identifiant'] = self::normalizePhone($validated['identifiant']);
        }

        return $validated;
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

    private static function firstFilledString(mixed ...$values): ?string
    {
        foreach ($values as $value) {
            if (is_string($value)) {
                $value = trim($value);

                if ($value !== '') {
                    return $value;
                }
            }
        }

        return null;
    }

    private static function normalizePhone(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? $value;
    }
}
