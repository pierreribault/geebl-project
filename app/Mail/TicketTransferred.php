<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketTransferred extends Mailable
{
    use Queueable, SerializesModels;

    private Ticket $ticket;

    private User $fromUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $fromUser, Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->fromUser = $fromUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $qrcode = QrCode::format('png')->generate($this->ticket->id);

        return $this
            ->view('mails.tickets.transferred')->subject("{$this->fromUser->name} given you a ticket for {$this->ticket->event->name}")
            ->with([
                'ticket' => $this->ticket,
                // 'qrcode' => $qrcode,
                'fromUser' => $this->fromUser,
            ]);
    }
}
