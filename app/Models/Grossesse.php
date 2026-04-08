<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
