<?php

namespace App\Models;

use App\Data\UserData;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\LaravelData\WithData;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Impersonate;
    use WithData;

    protected $dataClass = UserData::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_owner',
        'is_redactor',
        'is_reviewer',
        'is_consumer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //===> RELATIONSHIPS <==================================//

    public function company(): ?BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function events(): ?HasMany
    {
        return $this->hasMany(Event::class, 'author_id');
    }

    //===> STATUS <==================================//

    public function isProfesionnal(): bool
    {
        return $this->company()->exists();
    }

    public function isOwner(): bool
    {
        return $this->isProfesionnal() && $this->is_owner;
    }

    public function isRedactor(): bool
    {
        return $this->isProfesionnal() && $this->is_redactor;
    }

    public function isReviewer(): bool
    {
        return $this->isProfesionnal() && $this->is_reviewer;
    }

    public function isConsumer(): bool
    {
        return $this->isProfesionnal() && $this->is_consumer;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isInSameCompanyThan(User $user): bool
    {
        return $this->company_id === $user->company_id;
    }

    public function isInCompany(Company $company): bool
    {
        return $this->company_id === $company->id;
    }

    //===> SCOPES <==================================//

    public function scopeIsProfesionnal($query)
    {
        return $query->whereNotNull('company_id');
    }

    public function scopeIsOwner($query)
    {
        return $query->isProfesionnal()->where('is_owner', true);
    }

    public function scopeIsRedactor($query)
    {
        return $query->isProfesionnal()->where('is_redactor', true);
    }

    public function scopeIsReviewer($query)
    {
        return $query->isProfesionnal()->where('is_reviewer', true);
    }

    public function scopeIsConsumer($query)
    {
        return $query->isProfesionnal()->where('is_consumer', true);
    }

    //===> PERMISSIONS <==================================//

    public function canImpersonate()
    {
        return $this->is_admin;
    }

    public function canBeImpersonated()
    {
        return !$this->is_admin;
    }
}
