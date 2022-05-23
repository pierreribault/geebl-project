<?php

namespace App\Enums;

use App\Traits\Enums\ArrayRender;

enum Carousels: String
{
    use ArrayRender;

    case Trending = 'trending';
    case House = 'house';

    public static function label(String $key): String
    {
        return __("carousels.labels.{$key}");
    }
}
