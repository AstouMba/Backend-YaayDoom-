<?php

namespace App\Application\Bebe;

use App\Models\Bebe;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListBebes extends Service
{
    public function execute(): Collection
    {
        return Bebe::all();
    }
}
