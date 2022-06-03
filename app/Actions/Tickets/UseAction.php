<?php

namespace App\Actions\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;

class UseAction
{
    public function use(Ticket $ticket): Ticket
    {
        $originalStatus = $ticket->getOriginal('status');

        $ticket->status = TicketStatus::Used->value;
        $ticket->save();

        // set a virtual status to indicate that the ticket was used
        if ($originalStatus === TicketStatus::Used->value) {
            $ticket->status = TicketStatus::ReUsed->value; 
        }

        return $ticket;
    }
}
