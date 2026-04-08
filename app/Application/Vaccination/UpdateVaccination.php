<?php

namespace App\Application\Vaccination;

use App\Application\Vaccination\DTO\UpdateVaccinationData;
use App\Models\Vaccination;
use App\Services\Service;

class UpdateVaccination extends Service
{
    public function execute(string $id, UpdateVaccinationData $data): Vaccination
    {
        $vaccination = Vaccination::find($id);

        if (!$vaccination) {
            $this->notFound('vaccination_not_found');
        }

        $vaccination->update(array_filter([
            'bebe_id' => $data->bebeId,
            'nom_vaccin' => $data->nomVaccin,
            'age' => $data->age,
            'date_vaccination' => $data->dateVaccination,
            'prochaine_dose' => $data->prochaineDose,
            'notes' => $data->notes,
            'professionnel_id' => $data->professionnelId,
        ], static fn (mixed $value): bool => $value !== null));

        return $vaccination;
    }
}
