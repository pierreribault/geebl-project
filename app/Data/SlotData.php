<?php

namespace App\Data;

use App\Enums\SlotStatus;
use App\Models\Slot;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class SlotData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $transation,
        public readonly ?int $quantity,
        public readonly Lazy|UserData $user,
        public readonly Lazy|EventData $event,
        #[DataCollectionOf(TicketData::class)]
        public readonly null|Lazy|DataCollection $tickets,
        #[WithCast(EnumCast::class)]
        public readonly ?SlotStatus $status = SlotStatus::Pending
    ) {
    }

    public static function fromModel(Slot $slot): self
    {
        return self::from([
            ...$slot->toArray(),
            'user' => Lazy::create(static fn () => UserData::from($slot->user)),
            'event' => Lazy::create(static fn () => EventData::from($slot->event)),
            'tickets' => Lazy::create(static fn () => TicketData::collection($slot->tickets)),
        ]);
    }
}
