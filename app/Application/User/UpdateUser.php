<?php

namespace App\Application\User;

use App\Application\User\DTO\UpdateUserData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class UpdateUser extends Service
{
    public function execute(string $id, UpdateUserData $data): User
    {
        $user = User::find($id);

        if (!$user) {
            $this->notFound('user_not_found');
        }

        $user->name = $data->name ?? $user->name;
        $user->email = $data->email ?? $user->email;
        $user->phone = $data->phone ?? $user->phone;

        if ($data->password !== null) {
            $user->password = Hash::make($data->password);
        }

        if ($data->role !== null) {
            $user->role = $data->role;
        }

        if ($data->status !== null) {
            $user->status = $data->status;
        }

        if ($data->isValidated !== null) {
            $user->is_validated = $data->isValidated;
        }

        if ($data->specialite !== null) {
            $user->specialite = $data->specialite;
        }

        if ($data->matricule !== null) {
            $user->matricule = $data->matricule;
        }

        if ($data->centreDeSante !== null) {
            $user->centre_de_sante = $data->centreDeSante;
        }

        if ($data->rejectionReason !== null) {
            $user->rejection_reason = $data->rejectionReason;
        }

        $user->save();

        return $user;
    }
}
