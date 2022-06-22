<?php

namespace App\Enums;

enum ArticleStatus: String
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Published = 'published';

    public static function toSelectArray(): array
    {
        return collect(self::cases())->pluck('name', 'value')->toArray();
    }

    public static function colors(): array
    {
        return [
            self::Draft->value => 'warning',
            self::Published->value => 'success',
            self::Scheduled->value => 'info',
        ];
    }
}
