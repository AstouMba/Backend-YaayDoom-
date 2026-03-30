<?php

namespace Database\Seeders;

use App\Models\Grossesse;
use App\Models\User;
use Illuminate\Database\Seeder;

class GrossesseSeeder extends Seeder
{
    public function run(): void
    {
        $maman = User::where('email', 'maman@demo.com')->firstOrFail();

        Grossesse::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'date_debut' => '2026-01-10',
            ],
            [
                'date_fin_prevue' => '2026-10-17',
                'statut' => 'en_cours',
                'notes' => 'Grossesse de suivi régulier, échographies et consultations planifiées.',
            ]
        );

        Grossesse::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'date_debut' => '2024-04-12',
            ],
            [
                'date_fin_prevue' => '2025-01-18',
                'statut' => 'terminee',
                'notes' => 'Grossesse arrivée à terme avec suivi postnatal effectué.',
            ]
        );
    }
}
