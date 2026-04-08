<?php

namespace App\Application\User;

use App\Models\User;
use App\Services\Service;

class FindUserByEmail extends Service
{
    public function execute(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
