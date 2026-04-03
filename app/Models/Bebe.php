<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Bebe extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bebes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'maman_id',
        'grossesse_id',
        'nom',
        'date_naissance',
        'sexe',
        'poids',
        'poids_actuel',
        'taille',
        'taille_actuelle',
        'groupe_sanguin',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'poids' => 'decimal:2',
            'poids_actuel' => 'decimal:2',
            'taille' => 'decimal:2',
            'taille_actuelle' => 'decimal:2',
        ];
    }

    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public function toContractArray(): array
    {
        $ageActuel = null;

        if ($this->date_naissance) {
            $months = Carbon::parse($this->date_naissance)->diffInMonths(Carbon::now());
            $ageActuel = $months . ' mois';
        }

        return [
            'id' => $this->id,
            'grossesse_id' => $this->grossesse_id,
            'maman_id' => $this->maman_id,
            'nom' => $this->nom,
            'date_naissance' => optional($this->date_naissance)->format('Y-m-d'),
            'sexe' => $this->sexe,
            'poids' => $this->poids,
            'taille' => $this->taille,
            'groupe_sanguin' => $this->groupe_sanguin,
            'poids_actuel' => $this->poids_actuel ?? $this->poids,
            'taille_actuelle' => $this->taille_actuelle ?? $this->taille,
            'age_actuel' => $ageActuel,
        ];
    }

    /**
     * Get the maman that owns the bebe.
     */
    public function maman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'maman_id');
    }

    /**
     * Get the grossesse that owns the bebe.
     */
    public function grossesse(): BelongsTo
    {
        return $this->belongsTo(Grossesse::class, 'grossesse_id');
    }

    /**
     * Get the vaccinations for the bebe.
     */
    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class, 'bebe_id');
    }

    /**
     * Get the scans for the bebe.
     */
    public function scans(): HasMany
    {
        return $this->hasMany(Scan::class, 'bebe_id');
    }
}
