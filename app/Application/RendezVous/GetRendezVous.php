<?php

namespace App\Application\RendezVous;

use App\Models\RendezVous;
use App\Services\Service;

class GetRendezVous extends Service
{
    public function execute(string $id): RendezVous
    {
        $rendezVous = RendezVous::find($id);

        if (!$rendezVous) {
            $this->notFound('rendez_vous_not_found');
        }

        return $rendezVous;
    }
}
