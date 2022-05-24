<?php

namespace App\Data;

use App\Models\Artist;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class ArtistData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public string $name,
        public string $slug,
    ) {
    }

    public static function fromModel(Artist $artist): self
    {
        return self::from([
            ...$artist->toArray(),
            'events' => Lazy::create(static fn () => EventData::collection($artist->events)),
        ]);
    }
}
