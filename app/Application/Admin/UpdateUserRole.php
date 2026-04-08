<?php

namespace App\Application\Admin;

use App\Models\User;
use App\Services\Service;

class UpdateUserRole extends Service
{
    public function execute(User $user, string $role): User
    {
        $user->role = $role;
        $user->save();

        return $user;
    }
}
