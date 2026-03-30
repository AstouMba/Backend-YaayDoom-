<?php

namespace Database\Seeders;

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
        User::updateOrCreate(
            ['email' => 'maman@demo.com'],
            [
                'name' => 'Aminata Diallo',
                'phone' => '+221771234567',
                'password' => Hash::make('demo1234'),
                'role' => 'maman',
                'status' => 'actif',
                'is_validated' => true,
            ]
        );
    }
}
