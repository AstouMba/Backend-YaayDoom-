<?php

namespace App\Application\Vaccination;

use App\Models\Vaccination;
use App\Services\Service;

class DeleteVaccination extends Service
{
    public function execute(string $id): bool
    {
        $vaccination = Vaccination::find($id);

        if (!$vaccination) {
            $this->notFound('vaccination_not_found');
        }

        return $vaccination->delete();
    }
}
