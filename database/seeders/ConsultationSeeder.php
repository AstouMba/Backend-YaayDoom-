<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $maman = User::where('role', 'maman')
            ->where('phone', '+221771234567')
            ->firstOrFail();
        $professionnel = User::where('email', 'pro@demo.com')->firstOrFail();

        Consultation::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'professionnel_id' => $professionnel->id,
                'date' => '2026-03-05',
                'heure' => '09:30:00',
            ],
            [
                'type' => 'Consultation prenatale',
                'notes' => 'Tension normale, conseils nutritionnels et prise de vitamines.',
            ]
        );

        Consultation::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'professionnel_id' => $professionnel->id,
                'date' => '2026-03-19',
                'heure' => '14:00:00',
            ],
            [
                'type' => 'Consultation de suivi',
                'notes' => 'Evolution satisfaisante, rappel du prochain rendez-vous.',
            ]
        );
    }
}
