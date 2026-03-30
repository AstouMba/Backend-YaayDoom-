<?php

namespace App\Features\Scan;

use App\Models\Bebe;
use App\Models\Carte;
use App\Models\Grossesse;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class ScanService
{
    /**
     * Récupérer tous les scans
     */
    public function getAll(): Collection
    {
        return Scan::all();
    }

    /**
     * Récupérer un scan par ID
     */
    public function get(int $id): ?Scan
    {
        return Scan::find($id);
    }

    /**
     * Créer un scan
     */
    public function create(array $data): Scan
    {
        return Scan::create($data);
    }

    /**
     * Mettre à jour un scan
     */
    public function update(string $id, array $data): ?Scan
    {
        $scan = Scan::find($id);
        
        if (!$scan) {
            return null;
        }

        $scan->update($data);
        
        return $scan;
    }

    /**
     * Supprimer un scan
     */
    public function delete(string $id): bool
    {
        $scan = Scan::find($id);
        
        if (!$scan) {
            return false;
        }

        return $scan->delete();
    }

    /**
     * Résout la valeur QR (scanner caméra ou upload image déjà décodée)
     *
     * @return array<string, mixed>|null
     */
    public function resolveFromQrCode(string $qrCode, ?int $professionnelId = null): ?array
    {
        $qrCode = trim($qrCode);
        if ($qrCode === '') {
            return null;
        }

        $bebe = $this->resolveBebeFromQr($qrCode);
        if ($bebe) {
            $this->traceScan($bebe->id, $qrCode, $professionnelId);
            return $this->formatBebePayload($bebe, $qrCode);
        }

        $maman = $this->resolveMamanFromQr($qrCode);
        if (!$maman) {
            return null;
        }

        return $this->formatMamanPayload($maman, $qrCode);
    }

    private function resolveBebeFromQr(string $qrCode): ?Bebe
    {
        $normalized = mb_strtolower($qrCode);

        if (preg_match('/^p-(\d+)$/i', $normalized, $matches)) {
            return Bebe::with('maman')->find((int) $matches[1]);
        }

        if (ctype_digit($normalized)) {
            return Bebe::with('maman')->find((int) $normalized);
        }

        $carte = $this->resolveCarteFromQr($qrCode);
        if ($carte) {
            return Bebe::with('maman')
                ->where('maman_id', $carte->maman_id)
                ->latest('id')
                ->first();
        }

        $byNom = Bebe::with('maman')
            ->whereRaw('LOWER(nom) like ?', ['%' . $normalized . '%'])
            ->latest('id')
            ->first();

        if ($byNom) {
            return $byNom;
        }

        $nameGuess = $this->extractNameGuess($qrCode);
        if ($nameGuess === null) {
            return null;
        }

        return Bebe::with('maman')
            ->whereRaw('LOWER(nom) like ?', ['%' . mb_strtolower($nameGuess) . '%'])
            ->latest('id')
            ->first();
    }

    private function resolveMamanFromQr(string $qrCode): ?User
    {
        $normalized = mb_strtolower($qrCode);

        if (preg_match('/^m-(\d+)$/i', $normalized, $matches)) {
            return User::find((int) $matches[1]);
        }

        if (preg_match('/^mam-(\d+)$/i', $normalized, $matches)) {
            return User::find((int) $matches[1]);
        }

        $carte = $this->resolveCarteFromQr($qrCode);
        if ($carte) {
            return User::find($carte->maman_id);
        }

        $nameGuess = $this->extractNameGuess($qrCode);
        if ($nameGuess !== null) {
            $byName = User::whereRaw('LOWER(name) like ?', ['%' . mb_strtolower($nameGuess) . '%'])
                ->where('role', 'maman')
                ->first();

            if ($byName) {
                return $byName;
            }
        }

        $grossesse = Grossesse::with('maman')
            ->whereRaw('LOWER(notes) like ?', ['%' . $normalized . '%'])
            ->latest('id')
            ->first();

        return $grossesse?->maman;
    }

    private function resolveCarteFromQr(string $qrCode): ?Carte
    {
        $qrUpper = mb_strtoupper($qrCode);

        $carte = Carte::where('numero_carte', $qrUpper)->first();
        if ($carte) {
            return $carte;
        }

        return Carte::all()->first(function (Carte $item) use ($qrUpper) {
            return str_starts_with($qrUpper, mb_strtoupper($item->numero_carte));
        });
    }

    private function extractNameGuess(string $qrCode): ?string
    {
        $parts = array_values(array_filter(explode('-', trim($qrCode))));
        if (count($parts) < 2) {
            return null;
        }

        // Exemple attendu : YD-2024-00156-AMINATA-DIALLO
        if (mb_strtoupper($parts[0]) === 'YD' && count($parts) >= 4) {
            return implode(' ', array_slice($parts, 3));
        }

        // Fallback générique : on tente avec les deux derniers segments
        return implode(' ', array_slice($parts, -2));
    }

    private function traceScan(int $bebeId, string $qrCode, ?int $professionnelId = null): void
    {
        Scan::create([
            'bebe_id' => $bebeId,
            'type_scan' => 'qr_code',
            'date_scan' => Carbon::today()->toDateString(),
            'resultat' => $qrCode,
            'notes' => $professionnelId ? 'Scan par professionnel #' . $professionnelId : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatBebePayload(Bebe $bebe, string $qrCode): array
    {
        $grossesse = Grossesse::where('maman_id', $bebe->maman_id)->latest('id')->first();
        $semaines = 0;

        if ($grossesse?->date_debut) {
            $semaines = (int) max(0, Carbon::parse($grossesse->date_debut)->diffInWeeks(Carbon::now()));
        }

        return [
            'source' => 'scan',
            'qr_code' => $qrCode,
            'type' => 'bebe',
            'id' => 'p-' . $bebe->id,
            'bebeId' => $bebe->id,
            'nom' => $bebe->nom,
            'dateDenaissance' => optional($bebe->date_naissance)->format('Y-m-d'),
            'sexe' => $bebe->sexe,
            'mamanId' => $bebe->maman_id,
            'nomMaman' => $bebe->maman?->name,
            'telephone' => $bebe->maman?->phone,
            'grossesse' => $grossesse ? [
                'id' => 'g-' . $grossesse->id,
                'mamanId' => $grossesse->maman_id,
                'mamanNom' => $bebe->maman?->name,
                'semaineGrossesse' => $semaines,
                'statut' => strtoupper($grossesse->statut === 'en_cours' ? 'VALIDEE' : $grossesse->statut),
                'dateDernieresRegles' => optional($grossesse->date_debut)->format('Y-m-d'),
                'datePresumeAccouchement' => optional($grossesse->date_fin_prevue)->format('Y-m-d'),
            ] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatMamanPayload(User $maman, string $qrCode): array
    {
        $grossesse = Grossesse::where('maman_id', $maman->id)->latest('id')->first();
        $semaines = 0;

        if ($grossesse?->date_debut) {
            $semaines = (int) max(0, Carbon::parse($grossesse->date_debut)->diffInWeeks(Carbon::now()));
        }

        return [
            'source' => 'scan',
            'qr_code' => $qrCode,
            'type' => 'grossesse',
            'id' => 'm-' . $maman->id,
            'mamanId' => $maman->id,
            'nomMaman' => $maman->name,
            'telephone' => $maman->phone,
            'email' => $maman->email,
            'grossesse' => $grossesse ? [
                'id' => 'g-' . $grossesse->id,
                'mamanId' => $grossesse->maman_id,
                'mamanNom' => $maman->name,
                'semaineGrossesse' => $semaines,
                'statut' => strtoupper($grossesse->statut === 'en_cours' ? 'VALIDEE' : $grossesse->statut),
                'dateDernieresRegles' => optional($grossesse->date_debut)->format('Y-m-d'),
                'datePresumeAccouchement' => optional($grossesse->date_fin_prevue)->format('Y-m-d'),
            ] : null,
        ];
    }
}
