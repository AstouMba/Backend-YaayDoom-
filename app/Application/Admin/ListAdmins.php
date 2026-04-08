<?php

namespace App\Application\Admin;

use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListAdmins extends Service
{
    public function execute(): Collection
    {
        return User::where('role', 'admin')->get();
    }
}
