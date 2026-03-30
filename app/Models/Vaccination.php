<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vaccination extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vaccinations';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bebe_id',
        'nom_vaccin',
        'date_vaccination',
        'prochaine_dose',
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
            'date_vaccination' => 'date',
            'prochaine_dose' => 'date',
        ];
    }

    /**
     * Get the bebe that owns the vaccination.
     */
    public function bebe(): BelongsTo
    {
        return $this->belongsTo(Bebe::class, 'bebe_id');
    }
}
