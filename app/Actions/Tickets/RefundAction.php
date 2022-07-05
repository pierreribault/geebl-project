<?php

namespace App\Actions\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Services\StripeService;
use Stripe\StripeClient;

class RefundAction
{
    public function refund(Ticket $ticket): void
    {
        if ($ticket->status !== TicketStatus::NonUsed->value) {
            throw new \Exception('Ticket must unused to be refunded.');
        }

        /** @var StripeClient $stripe */
        $stripe = app(StripeService::class);

        $stripe->refunds->create([
            'payment_intent' => $ticket->transaction,
            'amount' => $ticket->price * 100,
        ]);

        $ticket->status = TicketStatus::Refunded->value;
        $ticket->save();
    }
}
