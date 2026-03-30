<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            MamanSeeder::class,
            ProfessionnelSeeder::class,
            GrossesseSeeder::class,
            BebeSeeder::class,
            ConsultationSeeder::class,
            RendezVousSeeder::class,
            CarteSeeder::class,
            VaccinationSeeder::class,
            ScanSeeder::class,
        ]);
    }
}
