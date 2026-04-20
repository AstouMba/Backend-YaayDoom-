<?php

namespace App\Application\Grossesse;

use App\Models\Grossesse;
use App\Models\User;
use App\Services\Service;

class GetGrossesse extends Service
{
    public function execute(string $id, ?User $user = null): Grossesse
    {
        $query = Grossesse::query()->with('maman');

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $grossesse = $query->find($id);

        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
        }

        return $grossesse;
    }
}
