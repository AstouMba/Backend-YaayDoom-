<?php

namespace App\Application\Bebe;

use App\Application\Bebe\DTO\CreateBebeData;
use App\Models\Bebe;
use App\Services\Service;

class CreateBebe extends Service
{
    public function execute(CreateBebeData $data): Bebe
    {
        return Bebe::create([
            'maman_id' => $data->mamanId,
            'grossesse_id' => $data->grossesseId,
            'nom' => $data->nom,
            'date_naissance' => $data->dateNaissance,
            'sexe' => $data->sexe,
            'poids' => $data->poids,
            'poids_actuel' => $data->poidsActuel ?? $data->poids,
            'taille' => $data->taille,
            'taille_actuelle' => $data->tailleActuelle ?? $data->taille,
            'groupe_sanguin' => $data->groupeSanguin,
            'notes' => $data->notes,
        ]);
    }
}
