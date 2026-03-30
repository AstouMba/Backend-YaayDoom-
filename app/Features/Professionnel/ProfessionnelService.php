<?php

namespace App\Features\Professionnel;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class ProfessionnelService
{
    /**
     * Récupérer tous les professionnels
     */
    public function getAll(): Collection
    {
        return User::where('role', 'professionnel')->get();
    }

    /**
     * Récupérer un professionnel par ID
     */
    public function get(int $id): ?User
    {
        return User::where('role', 'professionnel')->find($id);
    }

    /**
     * Créer un professionnel
     */
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'professionnel',
            'status' => $data['status'] ?? 'actif',
            'is_validated' => $data['is_validated'] ?? false,
            'specialite' => $data['specialite'] ?? null,
            'matricule' => $data['matricule'] ?? null,
            'centre_de_sante' => $data['centre_de_sante'] ?? null,
        ]);
    }

    /**
     * Mettre à jour un professionnel
     */
    public function update(string $id, array $data): ?User
    {
        $professionnel = User::where('role', 'professionnel')->find($id);

        if (!$professionnel) {
            return null;
        }

        $professionnel->name = $data['name'] ?? $professionnel->name;
        $professionnel->email = $data['email'] ?? $professionnel->email;
        $professionnel->phone = $data['phone'] ?? $professionnel->phone;

        if (isset($data['password'])) {
            $professionnel->password = Hash::make($data['password']);
        }

        if (isset($data['status'])) {
            $professionnel->status = $data['status'];
        }

        if (array_key_exists('is_validated', $data)) {
            $professionnel->is_validated = (bool) $data['is_validated'];
        }

        if (array_key_exists('specialite', $data)) {
            $professionnel->specialite = $data['specialite'];
        }

        if (array_key_exists('matricule', $data)) {
            $professionnel->matricule = $data['matricule'];
        }

        if (array_key_exists('centre_de_sante', $data)) {
            $professionnel->centre_de_sante = $data['centre_de_sante'];
        }

        $professionnel->save();

        return $professionnel;
    }

    /**
     * Supprimer un professionnel
     */
    public function delete(string $id): bool
    {
        $professionnel = User::where('role', 'professionnel')->find($id);

        if (!$professionnel) {
            return false;
        }

        return $professionnel->delete();
    }
}
