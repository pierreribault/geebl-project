<?php

namespace App\Actions\Tickets;

use App\Enums\TicketStatus;
use App\Events\TicketTransferred;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class TransferAction
{
    public function transfer(Ticket $ticket, User $toUser): void
    {
        if ($ticket->status !== TicketStatus::NonUsed->value) {
            throw new \Exception('Ticket must unused to be transferred.');
        }

        $fromUser = $ticket->user;

        $ticket->user()->associate($toUser);
        $ticket->save();

        Event::dispatch(new TicketTransferred($fromUser, $ticket));
    }
}
