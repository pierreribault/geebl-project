<?php

namespace App\Data;

use App\Enums\EventStatus;
use App\Models\Event;
use Carbon\Carbon;
use DateTime;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Lazy;

class EventData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $location,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly Carbon $date,
        public readonly string $description,
        #[Min(1)]
        public readonly float $price,
        #[Min(1)]
        public readonly int $seats,
        public readonly Lazy|SellerData $author,
        #[WithCast(EnumCast::class)]
        public readonly ?EventStatus $status = EventStatus::Draft,
    ) {
    }

    public static function fromModel(Event $event): self
    {
        return self::from([
            ...$event->toArray(),
            'author' => Lazy::create(fn () => SellerData::from($event->author)),
        ]);
    }
}
