<?php

namespace App\Models;

use App\Data\EventData;
use App\Enums\Carousels;
use Spatie\Tags\HasTags;
use App\Enums\EventStatus;
use App\Traits\UuidPrimaryKey;
use Laravel\Scout\Searchable;
use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory;
    use WithData;
    use Searchable;
    use HasTags;
    use UuidPrimaryKey;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'start_at',
        'end_at',
        'description',
        'price',
        'seats',
        'author_id',
        'company_id',
        'status',
    ];

    protected $casts = [
        'status' => EventStatus::class,
        'start_at' => 'date:Y-m-d',
        'end_at' => 'date:Y-m-d',
    ];

    protected $attributes = [
        'status' => EventStatus::Draft,
    ];

    protected $dataClass = EventData::class;

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tickets(): ?HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketsCategories(): ?HasMany
    {
        return $this->hasMany(TicketCategory::class);
    }

    public function artists(): ?BelongsToMany
    {
        return $this->belongsToMany(Artist::class);
    }

    public function news(): ?HasMany
    {
        return $this->hasMany(News::class);
    }

    public function scopeCarousel($query, $key, $value = null)
    {
        if ($key === Carousels::Trending) {
            return $query->where('status', EventStatus::Published)
                ->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
