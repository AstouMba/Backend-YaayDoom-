<?php

namespace App\Application\Consultation;

use App\Models\Consultation;
use App\Models\User;
use App\Services\Service;

class GetConsultation extends Service
{
    public function execute(string $id, ?User $user = null): Consultation
    {
        $query = Consultation::query()->with('maman', 'professionnel');

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $consultation = $query->find($id);

        if (!$consultation) {
            $this->notFound('consultation_not_found');
        }

        return $consultation;
    }
}
