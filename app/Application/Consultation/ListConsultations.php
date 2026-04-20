<?php

namespace App\Application\Consultation;

use App\Models\Consultation;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListConsultations extends Service
{
    public function execute(?User $user = null): Collection
    {
        $query = Consultation::query()->with('maman', 'professionnel');

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        return $query->latest('date')->get();
    }
}
