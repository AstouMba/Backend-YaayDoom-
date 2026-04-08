<?php

namespace App\Application\User;

use App\Models\User;
use App\Services\Service;

class GetUser extends Service
{
    public function execute(string $id): User
    {
        $user = User::find($id);

        if (!$user) {
            $this->notFound('user_not_found');
        }

        return $user;
    }
}
