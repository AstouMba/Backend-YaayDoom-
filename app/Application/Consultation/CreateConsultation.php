<?php

namespace App\Application\Consultation;

use App\Application\Consultation\DTO\CreateConsultationData;
use App\Models\Consultation;
use App\Services\Service;

class CreateConsultation extends Service
{
    public function execute(CreateConsultationData $data): Consultation
    {
        return Consultation::create([
            'maman_id' => $data->mamanId,
            'professionnel_id' => $data->professionnelId,
            'date' => $data->date,
            'heure' => $data->heure,
            'type' => $data->type,
            'tension_arterielle' => $data->tensionArterielle,
            'poids' => $data->poids,
            'hauteur_uterine' => $data->hauteurUterine,
            'bcf' => $data->bcf,
            'semaine_grossesse' => $data->semaineGrossesse,
            'notes' => $data->notes,
        ]);
    }
}
