<?php

namespace App\Jobs;

use App\Actions\QRCode\GenerateQRCode;
use App\Models\Slot;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
    public function __construct(protected Slot $slot)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $action = new GenerateQRCode();

        $tickets = Ticket::factory()
            ->for($this->slot)
            ->count($this->slot->quantity)
            ->unused()
            ->create();

        if ($tickets instanceof Collection) {
            $tickets->each(fn (Ticket $ticket) => $action->generate($ticket));
        } else {
            $action->generate($tickets);
        }
    }
}
