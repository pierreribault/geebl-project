<?php

namespace App\Mail;

use App\Actions\Tickets\GeneratePdfAction;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TicketsShipped extends Mailable
{
    use Queueable, SerializesModels;

    private Event $event;

    private array $tickets;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event, array $tickets)
    {
        $this->tickets = $tickets;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $attachments = [];

        /** @var Ticket $ticket */
        foreach ($this->tickets as $ticket) {
            $attachments[] = $ticket->getFirstMediaPath('invoice');
        }

        $email = $this
            ->view('mails.tickets.shipped')->subject("Your tickets for {$this->event->name}")
            ->with([
                'tickets' => $this->tickets,
                'event' => $this->event,
            ]);

        foreach ($attachments as $attachment) {
            $email->attach($attachment);
        }

        return $email;
    }
}
