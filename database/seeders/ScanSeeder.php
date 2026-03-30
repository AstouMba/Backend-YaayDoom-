<?php

namespace Database\Seeders;

use App\Models\Bebe;
use App\Models\Scan;
use Illuminate\Database\Seeder;

class ScanSeeder extends Seeder
{
    public function run(): void
    {
        $moussa = Bebe::where('nom', 'Moussa Diallo')->firstOrFail();
        $awa = Bebe::where('nom', 'Awa Diallo')->firstOrFail();

        Scan::updateOrCreate(
            [
                'bebe_id' => $moussa->id,
                'type_scan' => 'Echographie morphologique',
                'date_scan' => '2024-12-12',
            ],
            [
                'resultat' => 'Croissance conforme, aucun signe d anomalie detecte.',
                'notes' => 'Suivi morphologique de deuxième trimestre.',
            ]
        );

        Scan::updateOrCreate(
            [
                'bebe_id' => $awa->id,
                'type_scan' => 'Echographie de controle',
                'date_scan' => '2023-07-20',
            ],
            [
                'resultat' => 'Evolution normale, poids estimatif rassurant.',
                'notes' => 'Contrôle de routine après consultation.',
            ]
        );
    }
}
