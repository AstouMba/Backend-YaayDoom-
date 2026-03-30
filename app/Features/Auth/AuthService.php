<?php

namespace App\Features\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Inscrire un nouvel utilisateur
     */
    public function register(array $data): User
    {
        $role = $data['role'] ?? 'maman';

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $role,
            'is_validated' => $role === 'professionnel' ? false : true,
            'status' => 'actif',
            'specialite' => $data['specialite'] ?? null,
            'matricule' => $data['matricule'] ?? null,
            'centre_de_sante' => $data['centre_de_sante'] ?? null,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Connecter un utilisateur via email ou téléphone
     */
    public function login(string $login, string $password): ?array
    {
        $user = User::query()
            ->where('email', $login)
            ->orWhere('phone', $login)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        Auth::login($user);
        $token = $user->createToken('auth_token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Déconnecter un utilisateur
     */
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
        }
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function me(): ?User
    {
        return Auth::user();
    }

    /**
     * Mettre à jour le profil de l'utilisateur connecté
     */
    public function updateMe(array $data): ?User
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Changer le mot de passe de l'utilisateur connecté
     */
    public function changePassword(string $currentPassword, string $newPassword): void
    {
        $user = Auth::user();

        if (!$user) {
            throw ValidationException::withMessages([
                'user' => ['Utilisateur non authentifié.'],
            ]);
        }

        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
