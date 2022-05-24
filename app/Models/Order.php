<?php

namespace App\Models;

use App\Data\OrderData;
use App\Enums\OrderStatus;
use App\Traits\UuidPrimaryKey;
use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use UuidPrimaryKey;
    use HasFactory;
    use WithData;

    protected $fillable = [
        'transaction',
        'status',
        'user_id',
        'event_id',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected $attributes = [
        'status' => OrderStatus::Pending,
    ];

    protected $dataClass = OrderData::class;

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
