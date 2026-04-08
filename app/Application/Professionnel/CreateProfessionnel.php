<?php

namespace App\Application\Professionnel;

use App\Application\Professionnel\DTO\CreateProfessionnelData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class CreateProfessionnel extends Service
{
    public function execute(CreateProfessionnelData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'password' => Hash::make($data->password),
            'role' => 'professionnel',
            'status' => $data->status,
            'is_validated' => $data->isValidated,
            'specialite' => $data->specialite,
            'matricule' => $data->matricule,
            'centre_de_sante' => $data->centreDeSante,
        ]);
    }
}
