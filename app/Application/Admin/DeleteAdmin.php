<?php

namespace App\Application\Admin;

use App\Models\User;
use App\Services\Service;

class DeleteAdmin extends Service
{
    public function execute(string $id): bool
    {
        $admin = User::where('role', 'admin')->find($id);

        if (!$admin) {
            $this->notFound('admin_not_found');
        }

        return $admin->delete();
    }
}
