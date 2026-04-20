<?php

namespace App\Application\Bebe;

use App\Models\Bebe;
use App\Models\User;
use App\Services\Service;

class GetBebe extends Service
{
    public function execute(string $id, ?User $user = null): Bebe
    {
        $query = Bebe::query()->with('maman');

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $bebe = $query->find($id);

        if (!$bebe) {
            $this->notFound('bebe_not_found');
        }

        return $bebe;
    }
}
