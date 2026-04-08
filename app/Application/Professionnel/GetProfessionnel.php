<?php

namespace App\Application\Professionnel;

use App\Models\User;
use App\Services\Service;

class GetProfessionnel extends Service
{
    public function execute(string $id): User
    {
        $professionnel = User::where('role', 'professionnel')->find($id);

        if (!$professionnel) {
            $this->notFound('professionnel_not_found');
        }

        return $professionnel;
    }
}
