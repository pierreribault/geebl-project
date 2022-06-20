<?php

namespace App\Data;

use App\Enums\EventStatus;
use App\Enums\InvoiceStatus;
use App\Models\Country;
use App\Models\Invoice;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class InvoiceData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $transaction,
        public readonly int $quantity,
        public readonly float $price,
        public readonly float $total,
        public readonly null|Lazy|ProductData $product,
        public readonly null|Lazy|UserData $user,
        #[WithCast(EnumCast::class)]
        public readonly ?InvoiceStatus $status = InvoiceStatus::Pending,

    ) {
    }

    public static function fromModel(Invoice $invoice): self
    {
        return self::from([
            ...$invoice->toArray(),
            'product' => Lazy::create(fn () => ProductData::from($invoice->product)),
            'user' => Lazy::create(fn () => UserData::from($invoice->user)),
        ]);
    }
}
