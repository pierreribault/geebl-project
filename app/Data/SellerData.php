<?php

namespace App\Data;

use App\Models\Seller;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class SellerData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly bool $is_owner,
        public readonly bool $is_redactor,
        public readonly bool $is_reviewer,
        public readonly bool $is_consumer,
        public readonly Lazy|CompanyData $company,
        public readonly Lazy|UserData $user,
        #[DataCollectionOf(EventData::class)]
        public readonly null|Lazy|DataCollection $events,
    ) {
    }

    public static function fromModel(Seller $seller): self
    {
        return self::from([
            ...$seller->toArray(),
            'company' => Lazy::create(fn () => CompanyData::from($seller->company)),
            'user' => Lazy::create(fn () => UserData::from($seller->user)),
            'events' => Lazy::create(fn () => EventData::collection($seller->events)),
        ]);
    }
}
