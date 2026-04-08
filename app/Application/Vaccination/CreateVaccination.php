<?php

namespace App\Application\Vaccination;

use App\Application\Vaccination\DTO\CreateVaccinationData;
use App\Models\Vaccination;
use App\Services\Service;

class CreateVaccination extends Service
{
    public function execute(CreateVaccinationData $data): Vaccination
    {
        return Vaccination::create([
            'bebe_id' => $data->bebeId,
            'nom_vaccin' => $data->nomVaccin,
            'age' => $data->age,
            'date_vaccination' => $data->dateVaccination,
            'prochaine_dose' => $data->prochaineDose,
            'notes' => $data->notes,
            'professionnel_id' => $data->professionnelId,
        ]);
    }
}
