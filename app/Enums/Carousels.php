<?php

namespace App\Enums;

use App\Enums\Traits\ArrayRender;

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
