<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Grossesse extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grossesses';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'maman_id',
        'date_debut',
        'date_fin_prevue',
        'nombre_grossesses_precedentes',
        'antecedents_medicaux',
        'professionnel_validateur',
        'date_validation',
        'trimestre',
        'statut',
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
            'date_debut' => 'date',
            'date_fin_prevue' => 'date',
            'date_validation' => 'date',
            'nombre_grossesses_precedentes' => 'integer',
            'trimestre' => 'integer',
        ];
    }

    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public function toContractArray(): array
    {
        $weeks = 0;

        if ($this->date_debut) {
            $weeks = (int) max(0, Carbon::parse($this->date_debut)->diffInWeeks(Carbon::now()));
        }

        $trimestre = $weeks > 0 ? (int) min(3, max(1, (int) ceil($weeks / 13))) : 1;

        return [
            'id' => $this->id,
            'maman_id' => $this->maman_id,
            'maman_nom' => $this->maman?->name,
            'date_debut' => optional($this->date_debut)->format('Y-m-d'),
            'date_fin_prevue' => optional($this->date_fin_prevue)->format('Y-m-d'),
            'semaine_grossesse' => $weeks,
            'nombre_grossesses_precedentes' => (int) ($this->nombre_grossesses_precedentes ?? 0),
            'antecedents_medicaux' => $this->antecedents_medicaux ?? '',
            'statut' => $this->statut,
            'professionnel_validateur' => $this->professionnel_validateur,
            'date_validation' => optional($this->date_validation)->format('Y-m-d'),
            'trimestre' => (int) ($this->trimestre ?? $trimestre),
            'notes' => $this->notes ?? '',
        ];
    }

    /**
     * Get the maman that owns the grossesse.
     */
    public function maman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'maman_id');
    }

    /**
     * Get the bebes for the grossesse.
     */
    public function bebes(): HasMany
    {
        return $this->hasMany(Bebe::class, 'grossesse_id');
    }
}
