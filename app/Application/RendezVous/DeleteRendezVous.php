<?php

namespace App\Application\RendezVous;

use App\Models\RendezVous;
use App\Models\User;
use App\Services\Service;

class DeleteRendezVous extends Service
{
    public function execute(string $id, ?User $user = null): bool
    {
        $query = RendezVous::query();

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $rendezVous = $query->find($id);

        if (!$rendezVous) {
            $this->notFound('rendez_vous_not_found');
        }

        return $rendezVous->delete();
    }
}
