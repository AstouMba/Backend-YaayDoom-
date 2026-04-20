<?php

namespace App\Features\Admin;

use App\Application\Admin\ApproveProfessionnel;
use App\Application\Admin\DTO\UserFiltersData;
use App\Application\Admin\GetStats;
use App\Application\Admin\ListPendingProfessionnels;
use App\Application\Admin\ListUsersForDashboard;
use App\Application\Admin\RejectProfessionnel;
use App\Application\Admin\UpdateUserStatus;
use App\Features\User\UserPresenter;
use App\Models\User;
use App\Services\Service;

class AdminService extends Service
{
    public function __construct(
        private ListUsersForDashboard $listUsersForDashboard,
        private ListPendingProfessionnels $listPendingProfessionnels,
        private UpdateUserStatus $updateUserStatus,
        private ApproveProfessionnel $approveProfessionnelUseCase,
        private RejectProfessionnel $rejectProfessionnelUseCase,
        private GetStats $getStatsUseCase,
    ) {}

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
