<?php

namespace App\Application\Grossesse;

use App\Application\Grossesse\DTO\CreateGrossesseData;
use App\Models\Grossesse;
use App\Models\User;
use App\Services\Service;

class CreateGrossesse extends Service
{
    public function execute(CreateGrossesseData $data, ?User $user = null): Grossesse
    {
        if (!$user) {
            $this->unauthorized();
        }

        return Grossesse::create([
            'maman_id' => $user->id,
            'date_debut' => $data->dateDebut,
            'date_fin_prevue' => $data->dateFinPrevue,
            'nombre_grossesses_precedentes' => $data->nombreGrossessesPrecedentes,
            'antecedents_medicaux' => $data->antecedentsMedicaux,
            'professionnel_validateur' => $data->professionnelValidateur,
            'date_validation' => $data->dateValidation,
            'trimestre' => $data->trimestre,
            'statut' => $data->statut,
            'notes' => $data->notes,
        ]);
    }
}
