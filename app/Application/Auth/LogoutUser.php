<?php

namespace App\Application\Auth;

use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class LogoutUser extends Service
{
    public function execute(): void
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
        }
    }
}
