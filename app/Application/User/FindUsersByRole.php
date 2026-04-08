<?php

namespace App\Application\User;

use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class FindUsersByRole extends Service
{
    public function execute(string $role): Collection
    {
        return User::where('role', $role)->get();
    }
}
