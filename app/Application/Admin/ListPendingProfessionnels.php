<?php

namespace App\Application\Admin;

use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListPendingProfessionnels extends Service
{
    public function execute(): Collection
    {
        return User::query()
            ->where('role', 'professionnel')
            ->where('is_validated', false)
            ->latest()
            ->get()
            ->values();
    }
}
