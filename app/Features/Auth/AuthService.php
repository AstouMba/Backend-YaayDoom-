<?php

namespace App\Features\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService extends Service
{
    /**
     * Inscrire un nouvel utilisateur
     */
    public function register(array $data): User
    {
        $role = $data['role'] ?? 'maman';
        $data['name'] = $data['name'] ?? $data['fullName'] ?? null;
        $data['specialite'] = $data['specialite'] ?? $data['specialty'] ?? null;
        $data['centre_de_sante'] = $data['centre_de_sante'] ?? $data['healthCenter'] ?? null;

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
    public function login(array $data): array
    {
        $login = $data['login'] ?? $data['loginId'] ?? $data['email'] ?? null;

        if (!$login) {
            $this->unprocessable('login_required', [
                'login' => ['required'],
            ]);
        }

        $user = User::query()
            ->where('email', $login)
            ->orWhere('phone', $login)
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            $this->fail('invalid_credentials', 401);
        }

        if ($user->role === 'professionnel' && !$user->is_validated) {
            $this->fail('professional_pending', 403, 'forbidden', [
                'user' => $user->toContractArray(),
            ]);
        }

        if ($user->status === 'inactif') {
            $this->fail('inactive_account', 403, 'forbidden');
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
    public function updateMe(array $data, ?User $user = null): User
    {
        if (!$user) {
            $this->unauthorized();
        }

        $data['name'] = $data['name'] ?? $data['nom'] ?? $user->name;
        $data['phone'] = $data['phone'] ?? $data['telephone'] ?? $user->phone;

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Changer le mot de passe de l'utilisateur connecté
     */
    public function changePassword(array $data, ?User $user = null): void
    {
        if (!$user) {
            $this->unauthorized();
        }

        if (!Hash::check($data['currentPassword'], $user->password)) {
            $this->unprocessable('current_password_incorrect', [
                'currentPassword' => ['invalid'],
            ]);
        }

        $user->update([
            'password' => Hash::make($data['newPassword']),
        ]);
    }
}
