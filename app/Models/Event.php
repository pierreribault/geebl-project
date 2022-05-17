<?php

namespace App\Models;

use App\Data\EventData;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\LaravelData\WithData;

class Event extends Model
{
    use HasFactory;
    use WithData;

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
}
