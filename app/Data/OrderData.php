<?php

namespace App\Data;

use App\Enums\OrderStatus;
use App\Models\Order;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class OrderData extends Data
{
    public function __construct(
        public readonly ?string $string,
        public readonly ?string $transation,
        public readonly Lazy|UserData $user,
        public readonly Lazy|EventData $event,
        #[DataCollectionOf(TicketData::class)]
        public readonly null|Lazy|DataCollection $tickets,
        #[WithCast(EnumCast::class)]
        public readonly ?OrderStatus $status = OrderStatus::Pending
    ) {
    }

    public static function fromModel(Order $order): self
    {
        return self::from([
            ...$order->toArray(),
            'user' => Lazy::create(static fn () => UserData::from($order->user)),
            'event' => Lazy::create(static fn () => EventData::from($order->event)),
            'tickets' => Lazy::create(static fn () => TicketData::collection($order->tickets)),
        ]);
    }
}
