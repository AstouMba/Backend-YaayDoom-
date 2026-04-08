<?php

namespace App\Application\Vaccination;

use App\Models\Vaccination;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListVaccinations extends Service
{
    public function execute(): Collection
    {
        return Vaccination::all();
    }
}
