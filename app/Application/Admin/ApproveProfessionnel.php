<?php

namespace App\Application\Admin;

use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Carbon;

class ApproveProfessionnel extends Service
{
    public function execute(User $user, string $motif, ?User $admin = null): User
    {
        if ($user->role !== 'professionnel') {
            $this->unprocessable('not_a_professional');
        }

        if (!is_array($user->verification_documents ?? null) || count($user->verification_documents) === 0) {
            $this->unprocessable('verification_documents_required');
        }

        $user->is_validated = true;
        $user->status = 'actif';
        $user->rejection_reason = null;
        $user->decision_status = 'approved';
        $user->decision_motif = $motif;
        $user->decision_date = Carbon::now();
        $user->decision_by = $admin?->id;
        $user->save();

        return $user;
    }
}
