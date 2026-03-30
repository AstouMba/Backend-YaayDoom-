<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that should be cast.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The key type for the model.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_validated',
        'status',
        'specialite',
        'matricule',
        'centre_de_sante',
        'rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_validated' => 'boolean',
        ];
    }

    /**
     * Get the consultations for the user (as maman).
     */
    public function consultationsAsMaman(): HasMany
    {
        return $this->hasMany(Consultation::class, 'maman_id');
    }

    /**
     * Get the consultations for the user (as professionnel).
     */
    public function consultationsAsProfessionnel(): HasMany
    {
        return $this->hasMany(Consultation::class, 'professionnel_id');
    }

    /**
     * Get the grossesses for the user.
     */
    public function grossesses(): HasMany
    {
        return $this->hasMany(Grossesse::class, 'maman_id');
    }

    /**
     * Get the bebes for the user.
     */
    public function bebes(): HasMany
    {
        return $this->hasMany(Bebe::class, 'maman_id');
    }

    /**
     * Get the rendez-vous for the user (as maman).
     */
    public function rendezVousAsMaman(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'maman_id');
    }

    /**
     * Get the rendez-vous for the user (as professionnel).
     */
    public function rendezVousAsProfessionnel(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'professionnel_id');
    }

    /**
     * Get the carte for the user.
     */
    public function carte(): HasOne
    {
        return $this->hasOne(Carte::class, 'maman_id');
    }
}
