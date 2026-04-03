<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

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
        'age',
        'date_vaccination',
        'prochaine_dose',
        'notes',
        'professionnel_id',
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
     * Format contractuel exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public function toContractArray(): array
    {
        $age = null;

        if ($this->bebe?->date_naissance && $this->date_vaccination && $this->bebe->date_naissance->equalTo($this->date_vaccination)) {
            $age = 'À la naissance';
        }

        return [
            'id' => $this->id,
            'bebe_id' => $this->bebe_id,
            'nom_vaccin' => $this->nom_vaccin,
            'age' => $this->age ?? $age,
            'date_vaccination' => optional($this->date_vaccination)->format('Y-m-d'),
            'prochaine_dose' => optional($this->prochaine_dose)->format('Y-m-d'),
            'notes' => $this->notes,
            'professionnel_id' => $this->professionnel_id,
        ];
    }

    /**
     * Get the bebe that owns the vaccination.
     */
    public function bebe(): BelongsTo
    {
        return $this->belongsTo(Bebe::class, 'bebe_id');
    }

    /**
     * Get the professionnel that owns the vaccination.
     */
    public function professionnel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professionnel_id');
    }
}
