<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'taille',
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
            'taille' => 'decimal:2',
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
