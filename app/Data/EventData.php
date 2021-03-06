<?php

namespace App\Data;

use Carbon\Carbon;
use App\Models\Event;
use App\Enums\EventStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class EventData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $location,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly Carbon $start_at,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly Carbon $end_at,
        public readonly string $description,
        public readonly ?string $cover_url,
        public readonly Lazy|UserData $author,
        public readonly Lazy|string $city_id,
        public readonly null|Lazy|DataCollection $kinds,
        public readonly null|Lazy|DataCollection $artists,
        public readonly null|Lazy|DataCollection $categories,
        public readonly null|Lazy|DataCollection $news,
        #[WithCast(EnumCast::class)]
        public readonly ?EventStatus $status = EventStatus::Draft,
    ) {
    }

    public static function fromModel(Event $event): self
    {
        return self::from([
            ...$event->toArray(),
            'cover_url' => $event->getFirstMediaUrl('cover'),
            'author' => Lazy::create(fn () => UserData::from($event->author)),
            'kinds' => Lazy::create(fn () => TagData::collection($event->tags)),
            'artists' => Lazy::create(fn () => ArtistData::collection($event->artists)),
            'categories' => Lazy::create(fn () => TicketCategoryData::collection($event->ticketsCategories)),
            'news' => Lazy::create(fn () => NewsData::collection($event->news()->published()->get())),
            'city_id' => Lazy::create(fn () => CityData::from($event->city)->id),
        ]);
    }

    public function with(): array
    {
        return [
            'beautiful_date' => $this->start_at->toFormattedDateString(),
        ];
    }
}
