<?php

namespace App\Listeners;

use Stripe\Charge;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Mail\TicketsShipped;
use App\Enums\TicketStatus;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OnChargeSucceeded
{
    private Charge $charge;

    private ?User $user;

    private Event $event;

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
    public function handle($event)
    {
        /** @var Charge $paymentIntent */
        $charge = $event->charge;

        $email = $charge->metadata->email;
        $user = User::whereEmail($email)->first();
        $event = Event::find($charge->metadata->event);

        if (!$event instanceof Event) {
            Log::error('Event not found', ['charge_id' => $charge->id]);
            return;
        }

        $this->event = $event;
        $this->user = $user;
        $this->charge = $charge;

        $items = json_decode($this->charge->metadata->order);
        $tickets = [];

        foreach ($items as $item) {
            $category = TicketCategory::find($item->id);

            if (! $category) {
                continue;
            }

            foreach (range(1, $item->quantity) as $i) {
                $ticket = Ticket::create([
                    'price' => $category->price,
                    'user_id' => $this->user?->id,
                    'event_id' => $this->event->id,
                    'status' => TicketStatus::NonUsed->value,
                    'ticket_category_id' => $item->id,
                    'payment_intent_id' => $charge->payment_intent,
                ]);

                $tickets[] = $ticket;
            }
        }

        Mail::to($email)->send(new TicketsShipped($this->event, $tickets));
    }
}
