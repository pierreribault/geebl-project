<?php

namespace App\Enums;

use App\Traits\Enums\ArrayRender;

enum TicketStatus: String
{
    use ArrayRender;

    case Pending = 'pending';
    case NonUsed = 'non-used';
    case Used = 'used';
    case ReUsed = 're-used';
    case Refunded = 'refunded';
}
