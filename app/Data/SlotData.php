<?php

namespace App\Data;

use App\Enums\SlotStatus;
use App\Models\Slot;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Lazy;

class SlotData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $transation,
        public readonly ?string $quantity,
        public readonly Lazy|UserData $user,
        public readonly Lazy|EventData $event,
        #[WithCast(EnumCast::class)]
        public readonly ?SlotStatus $status = SlotStatus::Pending,
    ) {
    }

    public static function fromModel(Slot $slot): self
    {
        return self::from([
            ...$slot->toArray(),
            'user' => Lazy::create(fn () => UserData::from($slot->user)),
            'event' => Lazy::create(fn () => EventData::from($slot->event)),
        ]);
    }
}
