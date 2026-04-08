<?php

namespace App\Application\Bebe;

use App\Models\Bebe;
use App\Services\Service;

class DeleteBebe extends Service
{
    public function execute(string $id): bool
    {
        $bebe = Bebe::find($id);

        if (!$bebe) {
            $this->notFound('bebe_not_found');
        }

        return $bebe->delete();
    }
}
