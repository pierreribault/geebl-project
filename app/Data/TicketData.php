<?php

namespace App\Data;

use App\Models\Ticket;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class TicketData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $uuid,
        public readonly bool $used = false
    ) {
    }

    public static function fromModel(Ticket $ticket): self
    {
        return self::from([
            ...$ticket->toArray(),
            'category' => Lazy::create(static fn () => CategoryData::from($ticket->category)),
        ]);
    }
}
