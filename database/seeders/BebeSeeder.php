<?php

namespace Database\Seeders;

use App\Models\Bebe;
use App\Models\Grossesse;
use App\Models\User;
use Illuminate\Database\Seeder;

class BebeSeeder extends Seeder
{
    public function run(): void
    {
        $maman = User::where('role', 'maman')
            ->where('phone', '+221771234567')
            ->firstOrFail();
        $grossesseTerminee = Grossesse::where('maman_id', $maman->id)
            ->where('statut', 'terminee')
            ->first();

        Bebe::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'nom' => 'Moussa Diallo',
                'date_naissance' => '2025-01-18',
            ],
            [
                'grossesse_id' => $grossesseTerminee?->id,
                'sexe' => 'M',
                'poids' => 3.25,
                'taille' => 50.5,
                'notes' => 'Né à terme, bon état général.',
            ]
        );

        Bebe::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'nom' => 'Awa Diallo',
                'date_naissance' => '2023-08-04',
            ],
            [
                'grossesse_id' => null,
                'sexe' => 'F',
                'poids' => 2.95,
                'taille' => 48.0,
                'notes' => 'Suivi pédiatrique régulier, calendrier vaccinal à jour.',
            ]
        );
    }
}
