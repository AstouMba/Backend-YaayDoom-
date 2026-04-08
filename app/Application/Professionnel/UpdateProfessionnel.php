<?php

namespace App\Application\Professionnel;

use App\Application\Professionnel\DTO\UpdateProfessionnelData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class UpdateProfessionnel extends Service
{
    public function execute(string $id, UpdateProfessionnelData $data): User
    {
        $professionnel = User::where('role', 'professionnel')->find($id);

        if (!$professionnel) {
            $this->notFound('professionnel_not_found');
        }

        $professionnel->name = $data->name ?? $professionnel->name;
        $professionnel->email = $data->email ?? $professionnel->email;
        $professionnel->phone = $data->phone ?? $professionnel->phone;

        if ($data->password !== null) {
            $professionnel->password = Hash::make($data->password);
        }

        if ($data->status !== null) {
            $professionnel->status = $data->status;
        }

        if ($data->isValidated !== null) {
            $professionnel->is_validated = $data->isValidated;
        }

        if ($data->specialite !== null) {
            $professionnel->specialite = $data->specialite;
        }

        if ($data->matricule !== null) {
            $professionnel->matricule = $data->matricule;
        }

        if ($data->centreDeSante !== null) {
            $professionnel->centre_de_sante = $data->centreDeSante;
        }

        $professionnel->save();

        return $professionnel;
    }
}
