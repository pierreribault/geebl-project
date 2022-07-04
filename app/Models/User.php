<?php

namespace App\Models;

use App\Data\UserData;
use Spatie\LaravelData\WithData;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;

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
        'birthday',
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
        'birthday' => 'date:Y-m-d',
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

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'author_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
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

    public function scopeIsInCompany($query, Company $company)
    {
        return $query->whereRelation('company', 'id', $company->id);
    }

    //===> PERMISSIONS <==================================//

    public function canImpersonate()
    {
        return $this->is_admin;
    }

    public function canBeImpersonated(?AuthAuthenticatable $impersonator = null)
    {
        return !$this->is_admin;
    }

    public function invoice(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
