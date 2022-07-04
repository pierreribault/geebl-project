<?php

namespace App\Jobs;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Services\StripeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnfinishedPaymentTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tickets = Ticket::unfinishedPaymentProcess()->get();
        $transactions = $tickets->pluck('transaction')->unique();

        // Cancel stripe transaction
        $stripe = app(StripeService::class);
        $transactions->each(fn (string $transaction) => $stripe->paymentIntents->cancel($transaction));

        // Restore available tickets for next clients
        $tickets->each(fn (Ticket $ticket) => $ticket->delete());
    }
}
