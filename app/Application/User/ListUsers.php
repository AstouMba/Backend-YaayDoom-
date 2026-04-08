<?php

namespace App\Application\User;

use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListUsers extends Service
{
    public function execute(): Collection
    {
        return User::all();
    }
}
