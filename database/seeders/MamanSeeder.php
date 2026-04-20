<?php

namespace Database\Seeders;

use App\Models\Carte;
use App\Models\Consultation;
use App\Models\Grossesse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maman = User::updateOrCreate(
            ['phone' => '+221771234567'],
            [
                'name' => 'Aminata Diallo',
                'email' => null,
                'phone' => '+221771234567',
                'birth_date' => '1992-03-15',
                'password' => Hash::make('demo1234'),
                'role' => 'maman',
                'status' => 'actif',
                'is_validated' => true,
            ]
        );

        $professionnel = User::updateOrCreate(
            ['email' => 'pro@demo.com'],
            [
                'name' => 'Dr. Fatou Sow',
                'email' => 'pro@demo.com',
                'phone' => '+221762345679',
                'password' => Hash::make('demo1234'),
                'role' => 'professionnel',
                'status' => 'actif',
                'is_validated' => true,
                'specialite' => 'Gynécologue',
                'matricule' => 'GYN-2024-001',
                'centre_de_sante' => 'Hôpital Principal de Dakar',
            ]
        );

        Grossesse::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'date_debut' => '2026-01-10',
            ],
            [
                'date_fin_prevue' => '2026-10-17',
                'nombre_grossesses_precedentes' => 1,
                'antecedents_medicaux' => 'Suivi de grossesse sans complication majeure.',
                'professionnel_validateur' => $professionnel->id,
                'date_validation' => '2026-01-12',
                'trimestre' => 1,
                'statut' => 'validee',
                'notes' => 'Grossesse déclarée puis validée pour le suivi prénatal.',
            ]
        );

        Carte::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'numero_carte' => 'CARTE-2026-0001',
            ],
            [
                'date_emission' => '2026-01-15',
                'date_expiration' => '2028-01-15',
                'statut' => 'active',
            ]
        );

        Consultation::updateOrCreate(
            [
                'maman_id' => $maman->id,
                'professionnel_id' => $professionnel->id,
                'date' => '2026-03-05',
                'heure' => '09:30:00',
            ],
            [
                'type' => 'Consultation prénatale',
                'tension_arterielle' => '12/8',
                'poids' => 68.4,
                'hauteur_uterine' => 28,
                'bcf' => '145',
                'semaine_grossesse' => 10,
                'notes' => 'Contrôle de routine avec carte active et grossesse validée.',
            ]
        );
    }
}
