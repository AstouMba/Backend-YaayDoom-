<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consultations';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'maman_id',
        'professionnel_id',
        'date',
        'heure',
        'type',
        'tension_arterielle',
        'poids',
        'hauteur_uterine',
        'bcf',
        'semaine_grossesse',
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
            'poids' => 'decimal:2',
            'hauteur_uterine' => 'decimal:2',
            'semaine_grossesse' => 'integer',
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
            'professionnel_id' => $this->professionnel_id,
            'type' => $this->type,
            'date' => optional($this->date)->format('Y-m-d'),
            'heure' => $this->heure,
            'tension_arterielle' => $this->tension_arterielle,
            'poids' => $this->poids,
            'hauteur_uterine' => $this->hauteur_uterine,
            'bcf' => $this->bcf,
            'notes' => $this->notes,
            'semaine_grossesse' => $this->semaine_grossesse,
        ];
    }

    /**
     * Get the maman that owns the consultation.
     */
    public function maman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'maman_id');
    }

    /**
     * Get the professionnel that owns the consultation.
     */
    public function professionnel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professionnel_id');
    }
}
