<?php

namespace App\Features\Admin;

use App\Models\Consultation;
use App\Models\Grossesse;
use App\Models\User;
use App\Models\Vaccination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    /**
     * Récupérer tous les administrateurs
     */
    public function getAll(): Collection
    {
        return User::where('role', 'admin')->get();
    }

    /**
     * Récupérer un administrateur par ID
     */
    public function get(int $id): ?User
    {
        return User::where('role', 'admin')->find($id);
    }

    /**
     * Créer un administrateur
     */
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'status' => 'actif',
            'is_validated' => true,
        ]);
    }

    /**
     * Mettre à jour un administrateur
     */
    public function update(string $id, array $data): ?User
    {
        $admin = User::where('role', 'admin')->find($id);

        if (!$admin) {
            return null;
        }

        $admin->name = $data['name'] ?? $admin->name;
        $admin->email = $data['email'] ?? $admin->email;
        $admin->phone = $data['phone'] ?? $admin->phone;

        if (isset($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return $admin;
    }

    /**
     * Supprimer un administrateur
     */
    public function delete(string $id): bool
    {
        $admin = User::where('role', 'admin')->find($id);

        if (!$admin) {
            return false;
        }

        return $admin->delete();
    }

    /**
     * Liste utilisateurs pour le dashboard admin (filtres inclus)
     */
    public function getUsers(array $filters): array
    {
        $query = User::query()
            ->when(
                !empty($filters['role']) && $filters['role'] !== 'tous',
                fn (Builder $q): Builder => $q->where('role', $filters['role'])
            )
            ->when(
                !empty($filters['status']),
                fn (Builder $q): Builder => $q->where('status', $filters['status'])
            )
            ->when(!empty($filters['search']), function (Builder $q) use ($filters): Builder {
                $search = trim((string) $filters['search']);

                return $q->where(function (Builder $inner) use ($search): void {
                    $inner
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest();

        $users = $query->get();

        return $users
            ->map(fn (User $user): array => $this->formatUserForAdmin($user))
            ->values()
            ->all();
    }

    /**
     * Professionnels en attente de validation
     */
    public function getPendingProfessionnels(): array
    {
        return User::query()
            ->where('role', 'professionnel')
            ->where('is_validated', false)
            ->latest()
            ->get()
            ->map(fn (User $user): array => $this->formatUserForAdmin($user))
            ->values()
            ->all();
    }

    /**
     * Met à jour le rôle d'un utilisateur
     */
    public function updateRole(User $user, string $role): User
    {
        $user->role = $role;
        $user->save();

        return $user;
    }

    /**
     * Met à jour le statut actif/inactif d'un utilisateur
     */
    public function updateStatus(User $user, string $status): User
    {
        $user->status = $status;
        $user->save();

        return $user;
    }

    /**
     * Validation d'un professionnel
     */
    public function approveProfessionnel(User $user): User
    {
        $user->is_validated = true;
        $user->rejection_reason = null;
        $user->status = 'actif';
        $user->save();

        return $user;
    }

    /**
     * Rejet d'un professionnel
     */
    public function rejectProfessionnel(User $user, ?string $reason): User
    {
        $user->is_validated = false;
        $user->status = 'inactif';
        $user->rejection_reason = $reason;
        $user->save();

        return $user;
    }

    /**
     * Statistiques dashboard admin
     */
    public function getStats(): array
    {
        return [
            'totalMamans' => User::where('role', 'maman')->count(),
            'totalProfessionnels' => User::where('role', 'professionnel')->count(),
            'grossessesActives' => Grossesse::where('statut', 'en_cours')->count(),
            'professionnelsEnAttente' => User::where('role', 'professionnel')->where('is_validated', false)->count(),
            'consultationsTotal' => Consultation::count(),
            'vaccinationsTotal' => Vaccination::count(),
        ];
    }

    /**
     * Format cible attendu par les écrans admin du front
     */
    private function formatUserForAdmin(User $user): array
    {
        return [
            'id' => $user->id,
            'nom' => $user->name,
            'email' => $user->email,
            'telephone' => $user->phone,
            'role' => $user->role,
            'specialite' => $user->specialite,
            'matricule' => $user->matricule,
            'centreDesante' => $user->centre_de_sante,
            'isValidated' => (bool) $user->is_validated,
            'statut' => $user->status,
            'motifRejet' => $user->rejection_reason,
            'dateInscription' => optional($user->created_at)?->format('Y-m-d'),
        ];
    }
}
