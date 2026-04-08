<?php

namespace App\Application\Consultation;

use App\Models\Consultation;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListConsultations extends Service
{
    public function execute(): Collection
    {
        return Consultation::all();
    }
}
