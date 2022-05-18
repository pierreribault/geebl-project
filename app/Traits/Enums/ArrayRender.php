<?php

namespace App\Traits\Enums;

trait ArrayRender {
    public static function getKeysValues(): array
    {
        return collect(self::cases())->pluck('name', 'value')->toArray();
    }

    public static function getValues(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
