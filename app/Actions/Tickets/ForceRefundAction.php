<?php

namespace App\Actions\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Services\StripeService;
use Stripe\StripeClient;

class ForceRefundAction
{
    public function refund(Ticket $ticket): void
    {
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
