<?php

namespace App\Models;

use App\Data\TicketData;
use App\Traits\UuidPrimaryKey;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Actions\Tickets\GeneratePdfAction;
use App\Enums\TicketStatus;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model implements HasMedia
{
    use UuidPrimaryKey;
    use HasFactory;
    use WithData;
    use InteractsWithMedia;

    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_category_id',
        'status',
        'price',
        'transaction',
    ];

    protected $dataClass = TicketData::class;

    protected static function booted()
    {
        static::created(function ($ticket) {
            app(GeneratePdfAction::class)->generate($ticket);
        });
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

    public function scopeUnfinishedPaymentProcess($query)
    {
        return $query
            ->where('status', TicketStatus::Pending->value)
            ->whereDate('created_at', '<=', now()->subMinutes(10));
    }
}
