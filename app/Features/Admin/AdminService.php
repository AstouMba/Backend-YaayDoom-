<?php

namespace App\Features\Admin;

use App\Application\Admin\ApproveProfessionnel;
use App\Application\Admin\CreateAdmin;
use App\Application\Admin\DTO\CreateAdminData;
use App\Application\Admin\DTO\UpdateAdminData;
use App\Application\Admin\DTO\UserFiltersData;
use App\Application\Admin\DeleteAdmin;
use App\Application\Admin\GetAdmin;
use App\Application\Admin\GetStats;
use App\Application\Admin\ListAdmins;
use App\Application\Admin\ListPendingProfessionnels;
use App\Application\Admin\ListUsersForDashboard;
use App\Application\Admin\RejectProfessionnel;
use App\Application\Admin\UpdateAdmin;
use App\Application\Admin\UpdateUserRole;
use App\Application\Admin\UpdateUserStatus;
use App\Features\User\UserPresenter;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class AdminService extends Service
{
    public function __construct(
        private ListAdmins $listAdmins,
        private GetAdmin $getAdmin,
        private CreateAdmin $createAdmin,
        private UpdateAdmin $updateAdmin,
        private DeleteAdmin $deleteAdmin,
        private ListUsersForDashboard $listUsersForDashboard,
        private ListPendingProfessionnels $listPendingProfessionnels,
        private UpdateUserRole $updateUserRole,
        private UpdateUserStatus $updateUserStatus,
        private ApproveProfessionnel $approveProfessionnelUseCase,
        private RejectProfessionnel $rejectProfessionnelUseCase,
        private GetStats $getStatsUseCase,
    ) {}

    /**
     * Récupérer tous les administrateurs
     */
    public function getAll(): Collection
    {
        return $this->listAdmins->execute();
    }

    /**
     * Récupérer un administrateur par ID
     */
    public function get(string $id): ?User
    {
        return $this->getAdmin->execute($id);
    }

    /**
     * Créer un administrateur
     */
    public function create(array $data): User
    {
        return $this->createAdmin->execute(CreateAdminData::fromArray($data));
    }

    /**
     * Mettre à jour un administrateur
     */
    public function update(string $id, array $data): ?User
    {
        return $this->updateAdmin->execute($id, UpdateAdminData::fromArray($data));
    }

    /**
     * Supprimer un administrateur
     */
    public function delete(string $id): bool
    {
        return $this->deleteAdmin->execute($id);
    }

    /**
     * Liste utilisateurs pour le dashboard admin (filtres inclus)
     */
    public function getUsers(array $filters): array
    {
        $users = $this->listUsersForDashboard->execute(UserFiltersData::fromArray($filters));

        return $users
            ->map(fn (User $user): array => UserPresenter::admin($user))
            ->values()
            ->all();
    }

    /**
     * Professionnels en attente de validation
     */
    public function getPendingProfessionnels(): array
    {
        return $this->listPendingProfessionnels->execute()
            ->map(fn (User $user): array => UserPresenter::admin($user))
            ->values()
            ->all();
    }

    /**
     * Met à jour le rôle d'un utilisateur
     */
    public function updateRole(User $user, string $role): User
    {
        return $this->updateUserRole->execute($user, $role);
    }

    /**
     * Met à jour le statut actif/inactif d'un utilisateur
     */
    public function updateStatus(User $user, string $status): User
    {
        return $this->updateUserStatus->execute($user, $status);
    }

    /**
     * Validation d'un professionnel
     */
    public function approveProfessionnel(User $user, string $motif, ?User $admin = null): User
    {
        return $this->approveProfessionnelUseCase->execute($user, $motif, $admin);
    }

    /**
     * Rejet d'un professionnel
     */
    public function rejectProfessionnel(User $user, string $reason, ?User $admin = null): User
    {
        return $this->rejectProfessionnelUseCase->execute($user, $reason, $admin);
    }

    /**
     * Statistiques dashboard admin
     */
    public function getStats(): array
    {
        return $this->getStatsUseCase->execute();
    }

}
