<?php

namespace App\Features\User;

use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService extends Service
{
    /**
     * Créer un nouvel utilisateur
     */
    public function create(array $data): User
    {
        $data['name'] = $data['name'] ?? $data['nom'] ?? null;
        $data['phone'] = $data['phone'] ?? $data['telephone'] ?? null;
        $data['status'] = $data['status'] ?? $data['statut'] ?? 'actif';

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
            'status' => $data['status'],
            'is_validated' => $data['is_validated'] ?? true,
            'specialite' => $data['specialite'] ?? null,
            'matricule' => $data['matricule'] ?? null,
            'centre_de_sante' => $data['centre_de_sante'] ?? null,
            'rejection_reason' => $data['rejection_reason'] ?? null,
        ]);
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function get(string $id): ?User
    {
        $user = User::find($id);

        if (!$user) {
            $this->notFound('user_not_found');
        }

        return $user;
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function getAll(): Collection
    {
        return User::all();
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(string $id, array $data): ?User
    {
        $user = User::find($id);

        if (!$user) {
            $this->notFound('user_not_found');
        }

        $data['name'] = $data['name'] ?? $data['nom'] ?? $user->name;
        $data['phone'] = $data['phone'] ?? $data['telephone'] ?? $user->phone;
        $data['status'] = $data['status'] ?? $data['statut'] ?? $user->status;

        $user->name = $data['name'];
        $user->email = $data['email'] ?? $user->email;
        $user->phone = $data['phone'];

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (isset($data['role'])) {
            $user->role = $data['role'];
        }

        if (isset($data['status'])) {
            $user->status = $data['status'];
        }

        if (array_key_exists('is_validated', $data)) {
            $user->is_validated = (bool) $data['is_validated'];
        }

        if (array_key_exists('specialite', $data)) {
            $user->specialite = $data['specialite'];
        }

        if (array_key_exists('matricule', $data)) {
            $user->matricule = $data['matricule'];
        }

        if (array_key_exists('centre_de_sante', $data)) {
            $user->centre_de_sante = $data['centre_de_sante'];
        }

        if (array_key_exists('rejection_reason', $data)) {
            $user->rejection_reason = $data['rejection_reason'];
        }

        $user->save();

        return $user;
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(string $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            $this->notFound('user_not_found');
        }

        return $user->delete();
    }

    /**
     * Rechercher par email
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Rechercher par rôle
     */
    public function findByRole(string $role): Collection
    {
        return User::where('role', $role)->get();
    }
}
