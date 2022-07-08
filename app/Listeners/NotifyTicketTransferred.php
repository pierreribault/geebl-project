<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Ticket;
use App\Events\TicketTransferred as Event;
use App\Mail\TicketTransferred;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTicketTransferred
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        /** @var Ticket $ticket */
        $ticket = $event->ticket;

        /** @var User $fromUser */
        $fromUser = $event->fromUser;

        Mail::to($ticket->user->email)->send(new TicketTransferred($fromUser, $ticket));
    }
}
