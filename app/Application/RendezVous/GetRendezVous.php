<?php

namespace App\Application\RendezVous;

use App\Models\RendezVous;
use App\Models\User;
use App\Services\Service;

class GetRendezVous extends Service
{
    public function execute(string $id, ?User $user = null): RendezVous
    {
        $query = RendezVous::query()->with('professionnel');

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $rendezVous = $query->find($id);

        if (!$rendezVous) {
            $this->notFound('rendez_vous_not_found');
        }

        return $rendezVous;
    }
}
