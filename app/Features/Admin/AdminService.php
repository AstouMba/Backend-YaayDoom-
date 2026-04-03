<?php

namespace App\Features\Admin;

use App\Models\Consultation;
use App\Models\Grossesse;
use App\Models\User;
use App\Models\Vaccination;
use App\Services\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminService extends Service
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
    public function get(string $id): ?User
    {
        $admin = User::where('role', 'admin')->find($id);

        if (!$admin) {
            $this->notFound('admin_not_found');
        }

        return $admin;
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
            $this->notFound('admin_not_found');
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
            $this->notFound('admin_not_found');
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
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = max(1, (int) ($filters['per_page'] ?? 10));
        $users = $users->slice(($page - 1) * $perPage, $perPage)->values();

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
        if ($user->role !== 'professionnel') {
            $this->unprocessable('not_a_professional');
        }

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
        if ($user->role !== 'professionnel') {
            $this->unprocessable('not_a_professional');
        }

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
        $months = [
            1 => 'Jan',
            2 => 'Fév',
            3 => 'Mar',
            4 => 'Avr',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juil',
            8 => 'Août',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Déc',
        ];

        $grossessesParMois = [];
        foreach (range(1, 12) as $month) {
            $grossessesParMois[] = Grossesse::query()
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $month)
                ->count();
        }

        return [
            'totalMamans' => User::where('role', 'maman')->count(),
            'totalProfessionnels' => User::where('role', 'professionnel')->count(),
            'grossessesActives' => Grossesse::whereIn('statut', ['en_cours', 'validee'])->count(),
            'professionnelsEnAttente' => User::where('role', 'professionnel')->where('is_validated', false)->count(),
            'consultationsTotal' => Consultation::count(),
            'vaccinationsTotal' => Vaccination::count(),
            'grossessesParMois' => $grossessesParMois,
            'labelsParMois' => array_values($months),
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
            'documentUrl' => null,
            'dateInscription' => optional($user->created_at)?->format('Y-m-d'),
        ];
    }
}
