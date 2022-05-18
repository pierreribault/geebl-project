<?php

namespace App\Enums;

use App\Traits\Enums\ArrayRender;

enum EventStatus: String
{
    use ArrayRender;

    case Draft = 'draft';
    case Review = 'review';
    case Published = 'published';
    case Canceled = 'canceled';
}
