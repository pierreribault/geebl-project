<?php

namespace App\Data;

use App\Models\City;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class CityData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public string $name,
        public readonly Lazy|CountryData $country,
    ) {
    }

    public static function fromModel(City $city): self
    {
        return self::from([
            ...$city->toArray(),
            'country' => Lazy::create(static fn () => CountryData::from($city->country)),
        ]);
    }
}
