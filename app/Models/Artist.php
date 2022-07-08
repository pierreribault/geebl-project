<?php

namespace App\Models;

use App\Data\ArtistData;
use App\Traits\UuidPrimaryKey;
use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Artist extends Model implements HasMedia
{
    use UuidPrimaryKey;
    use HasFactory;
    use WithData;
    use InteractsWithMedia;

    public $timestamps = false;

    protected $dataClass = ArtistData::class;

    protected $fillable = [
        'name',
        'bio',
        'slug'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->useDisk('cover')->singleFile();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
