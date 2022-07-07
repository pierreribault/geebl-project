<?php

namespace App\Actions\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Services\StripeService;
use Stripe\StripeClient;

class TransferAction
{
    public function transfer(Ticket $ticket, User $toUser): void
    {
        if ($ticket->status !== TicketStatus::NonUsed->value) {
            throw new \Exception('Ticket must unused to be transferred.');
        }

        $ticket->user()->associate($toUser);
        $ticket->save();
    }
}
