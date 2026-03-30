<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Administrateur',
                'phone' => '+221700000001',
                'password' => Hash::make('demo1234'),
                'role' => 'admin',
                'status' => 'actif',
                'is_validated' => true,
            ]
        );
    }
}
