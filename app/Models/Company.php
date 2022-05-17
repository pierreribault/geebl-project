<?php

namespace App\Models;

use App\Data\CompanyData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\LaravelData\WithData;

class Company extends Model
{
    use HasFactory;
    use WithData;

    protected $dataClass = CompanyData::class;

    protected $fillable = [
        'name',
        'crn',
        'location',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getOwners()
    {
        return $this->users()->isOwner()->get();
    }

    public function invoice(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
