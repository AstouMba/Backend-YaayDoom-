<?php

namespace App\Application\Vaccination;

use App\Models\Vaccination;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListVaccinations extends Service
{
    public function execute(?User $user = null): Collection
    {
        $query = Vaccination::query()->with('bebe', 'professionnel');

        if ($user?->role === 'maman') {
            $query->whereHas('bebe', function ($bebeQuery) use ($user): void {
                $bebeQuery->where('maman_id', $user->id);
            });
        }

        return $query->latest('date_vaccination')->get();
    }
}
