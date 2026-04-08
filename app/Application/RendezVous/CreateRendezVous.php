<?php

namespace App\Application\RendezVous;

use App\Application\RendezVous\DTO\CreateRendezVousData;
use App\Models\Grossesse;
use App\Models\RendezVous;
use App\Services\Service;

class CreateRendezVous extends Service
{
    public function execute(CreateRendezVousData $data): RendezVous
    {
        $grossesse = Grossesse::find($data->grossesseId);

        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
        }

        return RendezVous::create([
            'grossesse_id' => $data->grossesseId,
            'maman_id' => $grossesse->maman_id,
            'professionnel_id' => $data->professionnelId,
            'type' => $data->type,
            'motif' => $data->motif,
            'date' => $data->date,
            'heure' => $data->heure,
            'lieu' => $data->lieu,
            'notes' => $data->notes,
            'statut' => $data->statut,
        ]);
    }
}
