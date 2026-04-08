<?php

namespace App\Application\Consultation;

use App\Models\Consultation;
use App\Services\Service;

class GetConsultation extends Service
{
    public function execute(string $id): Consultation
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            $this->notFound('consultation_not_found');
        }

        return $consultation;
    }
}
