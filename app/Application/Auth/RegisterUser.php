<?php

namespace App\Application\Auth;

use App\Application\Auth\DTO\RegisterData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class RegisterUser extends Service
{
    public function execute(RegisterData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'birth_date' => $data->birthDate,
            'password' => Hash::make($data->password),
            'role' => $data->role,
            'is_validated' => $data->role === 'professionnel' ? false : true,
            'status' => 'actif',
            'specialite' => $data->specialite,
            'matricule' => $data->matricule,
            'centre_de_sante' => $data->centreDeSante,
            'rejection_reason' => null,
        ]);
    }
}
