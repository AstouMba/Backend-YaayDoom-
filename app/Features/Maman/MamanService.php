<?php

namespace App\Features\Maman;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class MamanService
{
    /**
     * Récupérer toutes les mamans
     */
    public function getAll(): Collection
    {
        return User::where('role', 'maman')->get();
    }

    /**
     * Récupérer une maman par ID
     */
    public function get(int $id): ?User
    {
        return User::where('role', 'maman')->find($id);
    }

    /**
     * Créer une maman
     */
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'maman',
            'status' => $data['status'] ?? 'actif',
            'is_validated' => true,
        ]);
    }

    /**
     * Mettre à jour une maman
     */
    public function update(string $id, array $data): ?User
    {
        $maman = User::where('role', 'maman')->find($id);

        if (!$maman) {
            return null;
        }

        $maman->name = $data['name'] ?? $maman->name;
        $maman->email = $data['email'] ?? $maman->email;
        $maman->phone = $data['phone'] ?? $maman->phone;

        if (isset($data['password'])) {
            $maman->password = Hash::make($data['password']);
        }

        if (isset($data['status'])) {
            $maman->status = $data['status'];
        }

        $maman->save();

        return $maman;
    }

    /**
     * Supprimer une maman
     */
    public function delete(string $id): bool
    {
        $maman = User::where('role', 'maman')->find($id);

        if (!$maman) {
            return false;
        }

        return $maman->delete();
    }
}
