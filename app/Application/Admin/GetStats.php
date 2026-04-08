<?php

namespace App\Application\Admin;

use App\Models\Consultation;
use App\Models\Grossesse;
use App\Models\User;
use App\Models\Vaccination;
use App\Services\Service;
use Illuminate\Support\Carbon;

class GetStats extends Service
{
    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        $months = [
            1 => 'Jan',
            2 => 'Fév',
            3 => 'Mar',
            4 => 'Avr',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juil',
            8 => 'Août',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Déc',
        ];

        $grossessesParMois = [];
        foreach (range(1, 12) as $month) {
            $grossessesParMois[] = Grossesse::query()
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $month)
                ->count();
        }

        return [
            'totalMamans' => User::where('role', 'maman')->count(),
            'totalProfessionnels' => User::where('role', 'professionnel')->count(),
            'grossessesActives' => Grossesse::whereIn('statut', ['en_cours', 'validee'])->count(),
            'professionnelsEnAttente' => User::where('role', 'professionnel')->where('is_validated', false)->count(),
            'consultationsTotal' => Consultation::count(),
            'vaccinationsTotal' => Vaccination::count(),
            'grossessesParMois' => $grossessesParMois,
            'labelsParMois' => array_values($months),
        ];
    }
}
