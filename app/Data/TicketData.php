<?php

namespace App\Data;

use App\Models\Ticket;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class TicketData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $status,
        public readonly null|Lazy|UserData $user,
        public readonly Lazy|EventData $event,
        public readonly Lazy|EventData $category,
    ) {
    }

    public static function fromModel(Ticket $ticket): self
    {
        return self::from([
            ...$ticket->toArray(),
            'user' => Lazy::when(fn () => $ticket->user instanceof UserData, fn() => UserData::from($ticket->user)),
            'event' => Lazy::create(static fn () => EventData::from($ticket->event)),
            'category' => Lazy::create(static fn () => TicketCategoryData::from($ticket->category)),
        ]);
    }
}
