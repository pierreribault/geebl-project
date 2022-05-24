<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Enums\TicketStatus;
use App\Jobs\GenerateTicket;
use App\Models\Event;
use App\Models\User;
use App\Models\Order;
use App\Models\TicketCategory;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class CreateOrder
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
    public function handle($event)
    {
        /** @var Charge $paymentIntent */
        $charge = $event->charge;

        Log::info('debug', ['tibeault' => $charge]);

        $user = User::whereEmail($charge->metadata->email)->first();
        $event = Event::find($charge->metadata->event);

        if (!$event instanceof Event) {
            Log::error('Event not found', ['charge_id' => $charge->id]);
            return;
        }

        if ($user) {
            DB::transaction(function () use ($charge, $user, $event) {
                $order = Order::create([
                    'transaction' => $charge->id,
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'amount' => $charge->amount_captured,
                    'status' => OrderStatus::Completed->value,
                ]);

                foreach (json_decode($charge->metadata->order) as $item) {
                    $category = TicketCategory::find($item->id);

                    foreach (range(1, $item->quantity) as $i) {
                        $order->tickets()->create([
                            'price' => $category->price,
                            'user_id' => $user->id,
                            'event_id' => $event->id,
                            'order_id' => $order->id,
                            'status' => TicketStatus::NonUsed->value,
                            'ticket_category_id' => $item->id,
                        ]);
                    }
                }
            });
        }
    }
}
