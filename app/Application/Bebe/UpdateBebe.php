<?php

namespace App\Application\Bebe;

use App\Application\Bebe\DTO\UpdateBebeData;
use App\Models\Bebe;
use App\Services\Service;

class UpdateBebe extends Service
{
    public function execute(string $id, UpdateBebeData $data): Bebe
    {
        $bebe = Bebe::find($id);

        if (!$bebe) {
            $this->notFound('bebe_not_found');
        }

        $bebe->update(array_filter([
            'maman_id' => $data->mamanId,
            'grossesse_id' => $data->grossesseId,
            'nom' => $data->nom,
            'date_naissance' => $data->dateNaissance,
            'sexe' => $data->sexe,
            'poids' => $data->poids,
            'poids_actuel' => $data->poidsActuel,
            'taille' => $data->taille,
            'taille_actuelle' => $data->tailleActuelle,
            'groupe_sanguin' => $data->groupeSanguin,
            'notes' => $data->notes,
        ], static fn (mixed $value): bool => $value !== null));

        if ($data->poids !== null && $data->poidsActuel === null) {
            $bebe->poids_actuel = $data->poids;
        }

        if ($data->taille !== null && $data->tailleActuelle === null) {
            $bebe->taille_actuelle = $data->taille;
        }

        $bebe->save();

        return $bebe;
    }
}
