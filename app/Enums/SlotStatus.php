<?php

namespace App\Enums;

enum SlotStatus: String
{
    case Pending = 'pending';
    case Canceled = 'canceled';
    case Completed = 'completed';
    case Refund = 'refund';

    public static function toSelectArray(): array
    {
        return collect(EventStatus::cases())->pluck('name', 'value')->toArray();
    }
}
