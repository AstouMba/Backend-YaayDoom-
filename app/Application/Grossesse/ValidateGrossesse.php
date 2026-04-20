<?php

namespace App\Application\Grossesse;

use App\Models\Grossesse;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Carbon;

class ValidateGrossesse extends Service
{
    public function execute(string $id, User $user): Grossesse
    {
        $grossesse = Grossesse::query()
            ->with('maman')
            ->find($id);

        if (! $grossesse) {
            $this->notFound('grossesse_not_found');
        }

        $grossesse->update([
            'statut' => 'validee',
            'professionnel_validateur' => $user->id,
            'date_validation' => Carbon::now()->toDateString(),
        ]);

        return $grossesse->refresh();
    }
}
