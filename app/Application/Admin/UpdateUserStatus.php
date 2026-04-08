<?php

namespace App\Application\Admin;

use App\Models\User;
use App\Services\Service;

class UpdateUserStatus extends Service
{
    public function execute(User $user, string $status): User
    {
        $user->status = $status;
        $user->save();

        return $user;
    }
}
