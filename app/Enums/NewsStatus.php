<?php

namespace App\Enums;

enum NewsStatus: String
{
    case Draft = 'draft';
    case Review = 'review';
    case Pending = 'pending';
    case Published = 'published';
    case Canceled = 'canceled';

    public static function toSelectArray(): array
    {
        return collect(NewsStatus::cases())->pluck('name', 'value')->toArray();
    }
}
