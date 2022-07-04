<?php

namespace App\Data;

use App\Models\TicketCategory;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Lazy;

class TicketCategoryData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public string $name,
        public ?string $description,
        public float $price,
        public int $available_count,
        #[DataCollectionOf(EventData::class)]
        public Lazy|EventData $event,
    ) {
    }

    public static function fromModel(TicketCategory $category): self
    {
        return self::from([
            ...$category->toArray(),
            'event' => Lazy::create(static fn () => EventData::from($category->event)),
        ]);
    }
}
