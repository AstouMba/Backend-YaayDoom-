<?php

namespace App\Application\Grossesse;

use App\Application\Grossesse\DTO\UpdateGrossesseData;
use App\Models\Grossesse;
use App\Services\Service;

class UpdateGrossesse extends Service
{
    public function execute(string $id, UpdateGrossesseData $data): Grossesse
    {
        $grossesse = Grossesse::find($id);

        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
        }

        $grossesse->update(array_filter([
            'maman_id' => $data->mamanId,
            'date_debut' => $data->dateDebut,
            'date_fin_prevue' => $data->dateFinPrevue,
            'nombre_grossesses_precedentes' => $data->nombreGrossessesPrecedentes,
            'antecedents_medicaux' => $data->antecedentsMedicaux,
            'professionnel_validateur' => $data->professionnelValidateur,
            'date_validation' => $data->dateValidation,
            'trimestre' => $data->trimestre,
            'statut' => $data->statut,
            'notes' => $data->notes,
        ], static fn (mixed $value): bool => $value !== null));

        return $grossesse;
    }
}
