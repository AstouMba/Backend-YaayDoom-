<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rendez_vous';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'maman_id',
        'professionnel_id',
        'grossesse_id',
        'date',
        'heure',
        'type',
        'motif',
        'lieu',
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
            'date' => 'date',
        ];
    }

    /**
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public function toContractArray(): array
    {
        return [
            'id' => $this->id,
            'maman_id' => $this->maman_id,
            'grossesse_id' => $this->grossesse_id,
            'type' => $this->type,
            'motif' => $this->motif,
            'date' => optional($this->date)->format('Y-m-d'),
            'heure' => $this->heure,
            'professionnel_id' => $this->professionnel_id,
            'professionnel' => $this->professionnel?->name,
            'lieu' => $this->lieu,
            'statut' => $this->statut,
            'notes' => $this->notes,
        ];
    }

    /**
     * Get the maman that owns the rendez-vous.
     */
    public function maman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'maman_id');
    }

    /**
     * Get the professionnel that owns the rendez-vous.
     */
    public function professionnel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professionnel_id');
    }

    /**
     * Get the grossesse that owns the rendez-vous.
     */
    public function grossesse(): BelongsTo
    {
        return $this->belongsTo(Grossesse::class, 'grossesse_id');
    }
}
