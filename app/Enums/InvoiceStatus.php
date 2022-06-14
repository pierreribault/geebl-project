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
}
