<?php

namespace Database\Seeders;

use App\Models\Carte;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarteSeeder extends Seeder
{
    public function run(): void
    {
        $maman = User::where('email', 'maman@demo.com')->firstOrFail();

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
    }
}
