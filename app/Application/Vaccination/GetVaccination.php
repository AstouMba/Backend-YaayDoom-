<?php

namespace App\Application\Vaccination;

use App\Models\Vaccination;
use App\Services\Service;

class GetVaccination extends Service
{
    public function execute(string $id): Vaccination
    {
        $vaccination = Vaccination::find($id);

        if (!$vaccination) {
            $this->notFound('vaccination_not_found');
        }

        return $vaccination;
    }
}
