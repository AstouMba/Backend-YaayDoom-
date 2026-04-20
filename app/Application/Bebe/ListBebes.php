<?php

namespace App\Application\Bebe;

use App\Models\Bebe;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListBebes extends Service
{
    public function execute(?User $user = null): Collection
    {
        $query = Bebe::query()->with('maman');

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        return $query->latest('created_at')->get();
    }
}
