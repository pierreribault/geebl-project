<?php

namespace App\Models;

use App\Traits\UuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use UuidPrimaryKey;
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
