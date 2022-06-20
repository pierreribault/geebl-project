<?php

namespace App\Data;

use App\Models\Product;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $description,
        public readonly int $quantity,
        public readonly float $price,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        return self::from([
            ...$product->toArray(),
        ]);
    }
}
