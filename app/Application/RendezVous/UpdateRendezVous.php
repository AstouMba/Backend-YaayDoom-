<?php

namespace App\Application\RendezVous;

use App\Application\RendezVous\DTO\UpdateRendezVousData;
use App\Models\RendezVous;
use App\Models\User;
use App\Services\Service;

class UpdateRendezVous extends Service
{
    public function execute(string $id, UpdateRendezVousData $data, ?User $user = null): RendezVous
    {
        $query = RendezVous::query();

        if ($user?->role === 'maman') {
            $query->where('maman_id', $user->id);
        }

        $rendezVous = $query->find($id);

        if (!$rendezVous) {
            $this->notFound('rendez_vous_not_found');
        }

        $rendezVous->update(array_filter([
            'grossesse_id' => $data->grossesseId,
            'professionnel_id' => $data->professionnelId,
            'date' => $data->date,
            'heure' => $data->heure,
            'type' => $data->type,
            'motif' => $data->motif,
            'lieu' => $data->lieu,
            'notes' => $data->notes,
            'statut' => $data->statut,
        ], static fn (mixed $value): bool => $value !== null));

        return $rendezVous;
    }
}
