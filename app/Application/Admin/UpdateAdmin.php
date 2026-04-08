<?php

namespace App\Application\Admin;

use App\Application\Admin\DTO\UpdateAdminData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class UpdateAdmin extends Service
{
    public function execute(string $id, UpdateAdminData $data): User
    {
        $admin = User::where('role', 'admin')->find($id);

        if (!$admin) {
            $this->notFound('admin_not_found');
        }

        $admin->name = $data->name ?? $admin->name;
        $admin->email = $data->email ?? $admin->email;
        $admin->phone = $data->phone ?? $admin->phone;

        if ($data->password !== null) {
            $admin->password = Hash::make($data->password);
        }

        $admin->save();

        return $admin;
    }
}
