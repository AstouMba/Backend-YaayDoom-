<?php

namespace App\Application\User;

use App\Application\User\DTO\CreateUserData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Service
{
    public function execute(CreateUserData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'password' => Hash::make($data->password),
            'role' => $data->role,
            'status' => $data->status,
            'is_validated' => $data->isValidated,
            'specialite' => $data->specialite,
            'matricule' => $data->matricule,
            'centre_de_sante' => $data->centreDeSante,
            'rejection_reason' => $data->rejectionReason,
        ]);
    }
}
