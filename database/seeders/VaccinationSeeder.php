<?php

namespace Database\Seeders;

use App\Models\Bebe;
use App\Models\Vaccination;
use Illuminate\Database\Seeder;

class VaccinationSeeder extends Seeder
{
    public function run(): void
    {
        $moussa = Bebe::where('nom', 'Moussa Diallo')->firstOrFail();
        $awa = Bebe::where('nom', 'Awa Diallo')->firstOrFail();

        Vaccination::updateOrCreate(
            [
                'bebe_id' => $moussa->id,
                'nom_vaccin' => 'BCG',
                'date_vaccination' => '2025-01-18',
            ],
            [
                'prochaine_dose' => null,
                'notes' => 'Dose initiale administrée à la naissance.',
            ]
        );

        Vaccination::updateOrCreate(
            [
                'bebe_id' => $moussa->id,
                'nom_vaccin' => 'Pentavalent 1',
                'date_vaccination' => '2025-02-18',
            ],
            [
                'prochaine_dose' => '2025-03-18',
                'notes' => 'Première dose effectuée, rappel programmé.',
            ]
        );

        Vaccination::updateOrCreate(
            [
                'bebe_id' => $awa->id,
                'nom_vaccin' => 'Rougeole',
                'date_vaccination' => '2024-09-01',
            ],
            [
                'prochaine_dose' => null,
                'notes' => 'Vaccination faite lors du suivi de 9 mois.',
            ]
        );
    }
}
