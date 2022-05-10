<?php

namespace App\Data;

use App\Models\User;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Lazy;

class UserData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        #[Required, Email, Unique('users', 'email')]
        public readonly string $email,
        public readonly null|Lazy|SellerData $seller,
        public readonly bool $is_admin = false,
    ) {
    }

    public static function fromModel(User $user): self
    {
        return self::from([
            ...$user->toArray(),
            'seller' => Lazy::create(fn () => SellerData::from($user->seller)),
        ]);
    }
}
