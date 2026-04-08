<?php

namespace App\Application\Consultation;

use App\Models\Consultation;
use App\Services\Service;

class DeleteConsultation extends Service
{
    public function execute(string $id): bool
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            $this->notFound('consultation_not_found');
        }

        return $consultation->delete();
    }
}
