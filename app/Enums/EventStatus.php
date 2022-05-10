<?php

namespace App\Enums;

enum EventStatus: String
{
    case Draft = 'draft';
    case Review = 'review';
    case Published = 'published';
    case Canceled = 'canceled';

    public static function toSelectArray(): array
    {
        return collect(EventStatus::cases())->pluck('name', 'value')->toArray();
    }
}
