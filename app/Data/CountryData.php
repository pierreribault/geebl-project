<?php

namespace App\Data;

use App\Models\Country;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\DataCollection;

class CountryData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public string $name,
        public readonly null|Lazy|DataCollection $cities,
    ) {
    }

    public static function fromModel(Country $country): self
    {
        return self::from([
            ...$country->toArray(),
            'cities' => Lazy::create(static fn () => CityData::collection($country->cities)->include('event')),
        ]);
    }
}
