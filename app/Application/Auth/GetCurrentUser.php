<?php

namespace App\Application\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class GetCurrentUser extends Service
{
    public function execute(): ?User
    {
        return Auth::user();
    }
}
