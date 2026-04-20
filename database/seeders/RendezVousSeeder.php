<?php

namespace Database\Seeders;

use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Database\Seeder;

class RendezVousSeeder extends Seeder
{
    public function run(): void
    {
        $maman = User::where('role', 'maman')
            ->where('phone', '+221771234567')
            ->firstOrFail();
        $professionnel = User::where('email', 'pro@demo.com')->firstOrFail();

        RendezVous::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'professionnel_id' => $professionnel->id,
                'date' => '2026-04-02',
                'heure' => '10:00:00',
            ],
            [
                'motif' => 'Consultation prénatale de routine',
                'statut' => 'en_attente',
            ]
        );

        RendezVous::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'professionnel_id' => $professionnel->id,
                'date' => '2026-03-12',
                'heure' => '11:15:00',
            ],
            [
                'motif' => 'Suivi mensuel de grossesse',
                'statut' => 'confirme',
            ]
        );
    }
}
