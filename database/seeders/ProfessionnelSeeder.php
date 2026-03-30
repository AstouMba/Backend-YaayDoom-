<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfessionnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pro@demo.com'],
            [
                'name' => 'Dr Fatou Sow',
                'phone' => '+221762345678',
                'password' => Hash::make('demo1234'),
                'role' => 'professionnel',
                'status' => 'actif',
                'is_validated' => true,
                'specialite' => 'Gynecologue',
                'matricule' => 'GYN-2024-001',
                'centre_de_sante' => 'Hopital Principal de Dakar',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pro.enattente@demo.com'],
            [
                'name' => 'Dr Mariama Ba',
                'phone' => '+221763456789',
                'password' => Hash::make('demo1234'),
                'role' => 'professionnel',
                'status' => 'actif',
                'is_validated' => false,
                'specialite' => 'Sage-femme',
                'matricule' => 'SF-2024-012',
                'centre_de_sante' => 'Clinique Mere-Enfant',
            ]
        );
    }
}
