<?php

namespace App\Enums;

use App\Traits\Enums\ArrayRender;

enum TicketStatus: String
{
    use ArrayRender;

    case NonUsed = 'non-used';
    case Used = 'used';
    case Refunded = 'refunded';
}
