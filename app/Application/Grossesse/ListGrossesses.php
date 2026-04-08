<?php

namespace App\Application\Grossesse;

use App\Models\Grossesse;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class ListGrossesses extends Service
{
    public function execute(): Collection
    {
        return Grossesse::all();
    }
}
