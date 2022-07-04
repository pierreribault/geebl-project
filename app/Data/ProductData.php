<?php

namespace App\Data;

use App\Models\Product;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class ProductData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $description,
        public readonly int $quantity,
        public readonly float $price,
        public readonly string $product_url,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        return self::from([
            ...$product->toArray(),
            'product_url' => $product->getFirstMediaUrl('product'),
        ]);
    }

    public function with(): array
    {
        return [
            'short_content' => Str::limit($this->description, 400),
        ];
    }
}
