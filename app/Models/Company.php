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

    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class);
    }

    public function getOwners()
    {
        return $this->sellers()->where('is_owner', true)->get();
    }
}
