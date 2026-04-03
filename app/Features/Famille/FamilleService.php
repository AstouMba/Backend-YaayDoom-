<?php

namespace App\Features\Famille;

use App\Models\Bebe;
use App\Models\Consultation;
use App\Models\Grossesse;
use App\Models\RendezVous;
use App\Models\User;
use App\Models\Vaccination;
use App\Services\Service;

class FamilleService extends Service
{
    /**
     * Récupérer un dossier familial complet à partir de la maman.
     *
     * @return array<string, mixed>|null
     */
    public function get(string $mamanId): ?array
    {
        $maman = User::where('role', 'maman')->find($mamanId);
        if (!$maman) {
            $this->notFound('famille_not_found');
        }

        $grossesses = Grossesse::with('maman')
            ->where('maman_id', $maman->id)
            ->latest('created_at')
            ->get();

        $bebes = Bebe::with(['vaccinations', 'maman'])
            ->where('maman_id', $maman->id)
            ->latest('created_at')
            ->get();

        $consultations = Consultation::where('maman_id', $maman->id)
            ->latest('date')
            ->get();

        $rendezVous = RendezVous::with('professionnel')
            ->where('maman_id', $maman->id)
            ->latest('date')
            ->get();

        $vaccinations = Vaccination::with('bebe')
            ->whereIn('bebe_id', $bebes->pluck('id'))
            ->latest('date_vaccination')
            ->get();

        $membres = array_merge(
            [[
                'id' => $maman->id,
                'type' => 'maman',
                'lien' => 'mere',
                'nom' => $maman->name,
                'est_actif' => $maman->status === 'actif',
            ]],
            $bebes->map(fn (Bebe $bebe): array => [
                'id' => $bebe->id,
                'type' => 'bebe',
                'lien' => 'enfant',
                'nom' => $bebe->nom,
                'est_actif' => true,
            ])->all()
        );

        return [
            'id' => $maman->id,
            'maman_id' => $maman->id,
            'est_gemellaire' => $bebes->count() > 1,
            'membres' => $membres,
            'grossesses' => $grossesses->map(fn (Grossesse $grossesse): array => [
                'id' => $grossesse->id,
                'maman_id' => $grossesse->maman_id,
                'statut' => $grossesse->statut,
            ])->values()->all(),
            'bebes' => $bebes->map(fn (Bebe $bebe): array => [
                'id' => $bebe->id,
                'maman_id' => $bebe->maman_id,
                'nom' => $bebe->nom,
            ])->values()->all(),
            'consultations' => $consultations->map(fn (Consultation $consultation): array => $consultation->toContractArray())->values()->all(),
            'vaccinations' => $vaccinations->map(function (Vaccination $vaccination): array {
                $vaccination->loadMissing('bebe');
                return $vaccination->toContractArray();
            })->values()->all(),
            'rendez_vous' => $rendezVous->map(function (RendezVous $item): array {
                $item->loadMissing('professionnel');
                return $item->toContractArray();
            })->values()->all(),
        ];
    }

    /**
     * Récupérer le bloc maman du dossier.
     *
     * @return array<string, mixed>|null
     */
    public function getMaman(string $mamanId): ?array
    {
        $family = $this->get($mamanId);

        $maman = User::find($mamanId);
        $grossesse = $family['grossesses'][0] ?? null;

        return [
            'maman' => [
                'id' => $family['maman_id'],
                'nom' => $maman?->name,
                'email' => $maman?->email,
                'telephone' => $maman?->phone,
            ],
            'grossesse' => $grossesse ? [
                'id' => $grossesse['id'],
                'statut' => $grossesse['statut'],
            ] : null,
            'consultations' => $family['consultations'],
            'rendez_vous' => $family['rendez_vous'],
        ];
    }

    /**
     * Récupérer le bloc bébé du dossier.
     *
     * @return array<string, mixed>|null
     */
    public function getBebe(string $mamanId, string $bebeId): ?array
    {
        $bebe = Bebe::with('vaccinations')->where('maman_id', $mamanId)->find($bebeId);
        if (!$bebe) {
            $this->notFound('bebe_not_found');
        }

        return [
            'bebe' => [
                'id' => $bebe->id,
                'nom' => $bebe->nom,
                'date_naissance' => optional($bebe->date_naissance)->format('Y-m-d'),
                'sexe' => $bebe->sexe,
            ],
            'vaccinations' => $bebe->vaccinations->map(function (Vaccination $vaccination): array {
                $vaccination->loadMissing('bebe');
                return $vaccination->toContractArray();
            })->values()->all(),
            'croissance' => [],
        ];
    }
}
