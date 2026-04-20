<?php

namespace App\Application\Vaccination;

use App\Models\Vaccination;
use App\Models\User;
use App\Services\Service;

class DeleteVaccination extends Service
{
    public function execute(string $id, ?User $user = null): bool
    {
        $query = Vaccination::query();

        if ($user?->role === 'maman') {
            $query->whereHas('bebe', function ($bebeQuery) use ($user): void {
                $bebeQuery->where('maman_id', $user->id);
            });
        }

        $vaccination = $query->find($id);

        if (!$vaccination) {
            $this->notFound('vaccination_not_found');
        }

        return $vaccination->delete();
    }
}
