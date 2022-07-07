<?php

namespace App\Models;

use App\Data\CityData;
use App\Traits\UuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\LaravelData\WithData;

class City extends Model
{
    use UuidPrimaryKey;
    use HasFactory;
    use WithData;

    protected $fillable = [
        'name',
        'country_id',
        'event_id',
    ];

    protected $dataClass = CityData::class;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function event(): ?BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
