<?php

namespace App\Enums;

use App\Traits\Enums\ArrayRender;

enum OrderStatus: String
{
    use ArrayRender;

    case Pending = 'pending';
    case Canceled = 'canceled';
    case Completed = 'completed';
    case Refund = 'refund';
}
