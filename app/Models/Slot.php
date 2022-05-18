<?php

namespace App\Models;

use App\Data\SlotData;
use App\Enums\SlotStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\LaravelData\WithData;

class Slot extends Model
{
    use HasFactory;
    use WithData;

    protected $fillable = [
        'transaction',
        'quantity',
        'status',
    ];

    protected $casts = [
        'status' => SlotStatus::class,
    ];

    protected $attributes = [
        'status' => SlotStatus::Pending,
    ];

    protected $dataClass = SlotData::class;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tickets(): ?HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
