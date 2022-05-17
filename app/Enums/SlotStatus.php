<?php

namespace App\Enums;

use App\Enums\Traits\ArrayRender;

enum SlotStatus: String
{
    use ArrayRender;

    case Pending = 'pending';
    case Canceled = 'canceled';
    case Completed = 'completed';
    case Refund = 'refund';
}
