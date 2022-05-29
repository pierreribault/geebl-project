<?php

namespace App\Jobs;

use App\Actions\QRCode\GenerateQRCode;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class GenerateTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected TicketCategory $ticketCategory,
        protected Event $event,
        protected User $user = null,
    )
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $action = app(GenerateQRCode::class);

        $tickets = Ticket::factory()
            ->for($this->category)
            ->for($this->user)
            ->for($this->event)
            ->unused()
            ->create();

        if ($tickets instanceof Collection) {
            $tickets->each(fn (Ticket $ticket) => $action->generate($ticket));
        } else {
            $action->generate($tickets);
        }
    }
}
