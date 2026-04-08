<?php

namespace App\Application\Grossesse;

use App\Models\Grossesse;
use App\Services\Service;

class DeleteGrossesse extends Service
{
    public function execute(string $id): bool
    {
        $grossesse = Grossesse::find($id);

        if (!$grossesse) {
            $this->notFound('grossesse_not_found');
        }

        return $grossesse->delete();
    }
}
