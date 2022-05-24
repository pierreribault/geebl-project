<?php

namespace App\Models;

use App\Data\TicketData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{
    use HasFactory;
    use WithData;
    use InteractsWithMedia;

    public const QRCODE_MEDIA_COLLECTION = 'qrcode';

    protected $fillable = [
        'order_id',
        'event_id',
        'user_id',
        'ticket_category_id',
        'qrcode',
        'status',
        'price'
    ];

    protected $dataClass = TicketData::class;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static fn ($model) => $model->uuid = (string) Str::uuid());
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(self::QRCODE_MEDIA_COLLECTION)
            ->useDisk(self::QRCODE_MEDIA_COLLECTION)
            ->singleFile();
    }
}
