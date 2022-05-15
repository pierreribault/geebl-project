<?php

namespace App\Data;

use App\Models\User;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class UserData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        #[Required, Email, Unique('users', 'email')]
        public readonly string $email,
        public readonly bool $is_admin = false,
        public readonly bool $is_owner = false,
        public readonly bool $is_redactor = false,
        public readonly bool $is_reviewer = false,
        public readonly bool $is_consumer = false,
        public readonly null|Lazy|CompanyData $company,
        #[DataCollectionOf(EventData::class)]
        public readonly null|Lazy|DataCollection $events,
    ) {
    }

    public static function fromModel(User $user): self
    {
        return self::from([
            ...$user->toArray(),
            'company' => Lazy::create(fn () => CompanyData::from($user->company)),
            'events' => Lazy::create(fn () => EventData::collection($user->events)),
        ]);
    }
}
