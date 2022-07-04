<?php

namespace App\Enums;

enum InvoiceStatus: String
{
    case Pending = 'pending';
    case Canceled = 'canceled';
    case Completed = 'completed';
    case Refund = 'refund';

    public static function toSelectArray(): array
    {
        return collect(InvoiceStatus::cases())->pluck('name', 'value')->toArray();
    }

    public static function toNovaFilter(): array
    {
        return collect(InvoiceStatus::cases())->pluck('name', 'value')->flip()->toArray();
    }

    public static function colors(): array
    {
        return [
            self::Pending->value => 'warning',
            self::Canceled->value => 'danger',
            self::Completed->value => 'success',
            self::Refund->value => 'info',
        ];
    }
}
