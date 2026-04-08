<?php

namespace App\Application\Admin;

use App\Application\Admin\DTO\UserFiltersData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ListUsersForDashboard extends Service
{
    public function execute(UserFiltersData $filters): Collection
    {
        $query = User::query()
            ->when(
                $filters->role !== null && $filters->role !== 'tous',
                fn (Builder $q): Builder => $q->where('role', $filters->role)
            )
            ->when(
                $filters->status !== null && $filters->status !== '',
                fn (Builder $q): Builder => $q->where('status', $filters->status)
            )
            ->when($filters->search !== null && $filters->search !== '', function (Builder $q) use ($filters): Builder {
                $search = trim((string) $filters->search);

                return $q->where(function (Builder $inner) use ($search): void {
                    $inner
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest();

        return $query->get()
            ->slice(($filters->page - 1) * $filters->perPage, $filters->perPage)
            ->values()
            ->values();
    }
}
