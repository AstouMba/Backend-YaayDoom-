<?php

namespace App\Application\User;

use App\Models\User;
use App\Services\Service;

class DeleteUser extends Service
{
    public function execute(string $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            $this->notFound('user_not_found');
        }

        return $user->delete();
    }
}
