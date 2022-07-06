<?php

namespace App\Data;

use App\Models\Company;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class CompanyData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public string $name,
        public string $crn,
        public string $location,
        #[DataCollectionOf(UserData::class)]
        public null|Lazy|DataCollection $users,
    ) {
    }

    public static function fromModel(Company $company): self
    {
        return self::from([
            ...$company->toArray(),
            'users' => Lazy::create(static fn () => UserData::collection($company->users)),
        ]);
    }
}
