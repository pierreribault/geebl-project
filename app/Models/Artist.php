<?php

namespace App\Models;

use App\Data\ArtistData;
use App\Traits\UuidPrimaryKey;
use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artist extends Model
{
    use UuidPrimaryKey;
    use HasFactory;
    use WithData;

    public $timestamps = false;

    protected $dataClass = ArtistData::class;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
