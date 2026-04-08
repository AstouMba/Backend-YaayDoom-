<?php

namespace App\Application\Professionnel;

use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListProfessionnels extends Service
{
    public function execute(): Collection
    {
        return User::where('role', 'professionnel')->get();
    }
}
