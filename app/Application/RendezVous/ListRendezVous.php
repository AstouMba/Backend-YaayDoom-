<?php

namespace App\Application\RendezVous;

use App\Models\RendezVous;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListRendezVous extends Service
{
    public function execute(): Collection
    {
        return RendezVous::all();
    }
}
