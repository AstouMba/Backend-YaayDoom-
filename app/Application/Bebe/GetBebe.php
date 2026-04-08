<?php

namespace App\Application\Bebe;

use App\Models\Bebe;
use App\Services\Service;

class GetBebe extends Service
{
    public function execute(string $id): Bebe
    {
        $bebe = Bebe::find($id);

        if (!$bebe) {
            $this->notFound('bebe_not_found');
        }

        return $bebe;
    }
}
