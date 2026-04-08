<?php

namespace App\Application\Auth;

use App\Application\Auth\DTO\ChangePasswordData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Service
{
    public function execute(ChangePasswordData $data, ?User $user = null): void
    {
        if (!$user) {
            $this->unauthorized();
        }

        if (!Hash::check($data->currentPassword, $user->password)) {
            $this->unprocessable('current_password_incorrect', [
                'currentPassword' => ['invalid'],
            ]);
        }

        $user->update([
            'password' => Hash::make($data->newPassword),
        ]);
    }
}
