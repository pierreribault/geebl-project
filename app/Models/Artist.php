<?php

namespace App\Models;

use App\Data\ArtistData;
use App\Traits\UuidPrimaryKey;
use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;

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

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->useDisk('cover')->singleFile();
    }
}
