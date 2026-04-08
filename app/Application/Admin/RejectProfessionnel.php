<?php

namespace App\Application\Admin;

use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Carbon;

class RejectProfessionnel extends Service
{
    public function execute(User $user, string $reason, ?User $admin = null): User
    {
        if ($user->role !== 'professionnel') {
            $this->unprocessable('not_a_professional');
        }

        $user->is_validated = false;
        $user->status = 'inactif';
        $user->rejection_reason = $reason;
        $user->decision_status = 'rejected';
        $user->decision_motif = $reason;
        $user->decision_date = Carbon::now();
        $user->decision_by = $admin?->id;
        $user->save();

        return $user;
    }
}
