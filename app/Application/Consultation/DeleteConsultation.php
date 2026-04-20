<?php

namespace App\Application\Consultation;

use App\Models\Consultation;
use App\Models\User;
use App\Services\Service;

class DeleteConsultation extends Service
{
    public function execute(string $id, ?User $user = null): bool
    {
        $query = Consultation::query();

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $consultation = $query->find($id);

        if (!$consultation) {
            $this->notFound('consultation_not_found');
        }

        return $consultation->delete();
    }
}
