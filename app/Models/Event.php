<?php

namespace App\Models;

use App\Data\EventData;
use App\Enums\Carousels;
use App\Enums\EventStatus;
use Laravel\Scout\Searchable;
use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;
    use WithData;
    use Searchable;
    
    protected $fillable = [
        'name',
        'slug',
        'location',
        'date',
        'description',
        'price',
        'seats',
        'author_id',
        'company_id',
        'status',
    ];

    protected $casts = [
        'status' => EventStatus::class,
        'date' => 'date:Y-m-d',
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

    public function slots(): ?HasMany
    {
        return $this->hasMany(Slot::class);
    }

    public function scopeCarousel($query, $key, $value = null)
    {
        if ($key === Carousels::Trending) {
            return $query->where('status', EventStatus::Published)
                ->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
