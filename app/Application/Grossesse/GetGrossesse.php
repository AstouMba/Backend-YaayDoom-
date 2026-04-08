<?php

namespace App\Application\Grossesse;

use App\Models\Grossesse;
use App\Services\Service;

class GetGrossesse extends Service
{
    public function execute(string $id): Grossesse
    {
        $grossesse = Grossesse::find($id);

        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
        }

        return $grossesse;
    }
}
