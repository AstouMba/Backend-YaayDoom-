<?php

namespace App\Application\Admin;

use App\Application\Admin\DTO\CreateAdminData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Service
{
    public function execute(CreateAdminData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'password' => Hash::make($data->password),
            'role' => 'admin',
            'status' => 'actif',
            'is_validated' => true,
        ]);
    }
}
