<?php

namespace App\Application\Auth;

use App\Application\Auth\DTO\UpdateProfileData;
use App\Models\User;
use App\Services\Service;

class UpdateProfile extends Service
{
    public function execute(UpdateProfileData $data, ?User $user = null): User
    {
        if (!$user) {
            $this->unauthorized();
        }

        $user->update([
            'name' => $data->name ?? $user->name,
            'email' => $data->email ?? $user->email,
            'phone' => $data->phone ?? $user->phone,
            'specialite' => $data->specialite ?? $user->specialite,
            'centre_de_sante' => $data->centreDeSante ?? $user->centre_de_sante,
        ]);

        return $user->fresh();
    }
}
