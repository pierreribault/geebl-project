<?php

namespace App\Models;

use App\Data\EventData;
use App\Enums\EventStatus;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

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
        return $this->belongsTo(Seller::class, 'author_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function canBeViewedBy(Seller $seller): bool
    {
        return $this->author->company == $seller->company;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}