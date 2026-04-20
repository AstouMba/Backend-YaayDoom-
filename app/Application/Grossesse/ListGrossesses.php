<?php

namespace App\Application\Grossesse;

use App\Models\Grossesse;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListGrossesses extends Service
{
    public function execute(?User $user = null): Collection
    {
        $query = Grossesse::query()->with('maman');

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        return $query->latest('created_at')->get();
    }
}
