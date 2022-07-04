<?php

namespace App\Enums;

use App\Models\Product;

enum ProductStatus: String
{
    case InStock = 'in stock';
    case LowStock = 'low stock';
    case OutOfStock = 'out of stock';

    public static function available(int $quantity): string
    {
        return match (true) {
            $quantity >= Product::minimumBeforeLowStock => self::InStock->value,
            $quantity > 0 => self::LowStock->value,
            default => self::OutOfStock->value,
        };
    }

    public static function colors(): array
    {
        return [
            self::InStock->value => 'success',
            self::LowStock->value => 'warning',
            self::OutOfStock->value => 'danger',
        ];
    }

    public static function toSelectArray(): array
    {
        return collect(InvoiceStatus::cases())->pluck('name', 'value')->toArray();
    }
}
