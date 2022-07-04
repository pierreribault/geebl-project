<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use App\Actions\Events\SearchAction;
use App\Data\EventData;
use App\Enums\TicketStatus;
use App\Http\Requests\EventPreparePaymentRequest;
use App\Http\Resources\EventResource;
use App\Http\Requests\Events\SearchRequest;
use App\Http\Requests\EventSetupEmailRequest;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\StripeClient;

class EventController extends Controller
{
    public function search(SearchRequest $request, SearchAction $searcher)
    {
        return EventResource::collection(
            $searcher->search($request->all())
        );
    }

    public function show(Event $event)
    {
        return Inertia::render('Event/Show', [
            'event' => EventData::fromModel($event)->include(
                'kinds',
                'artists',
                'categories',
            ),
            'pspPublicKey' => config('services.stripe.public_key'),
        ]);
    }


    public function preparePayment(Event $event, EventPreparePaymentRequest $request)
    {
        $this->abortIfNotJson();

        $email = session('event.payment.email');

        /** @var StripeClient $stripe */
        $stripe = app(StripeService::class);
        $intent = $stripe->paymentIntents->create([
            'amount' => $request->getTotalAmountCents(),
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'email' => $email,
                'event' => $event->id,
                'order' => json_encode($request->get('order')),
            ]
        ]);

        collect($request->get('order'))->each(function ($order) use ($event, $intent) {
            $category = TicketCategory::where('id', $order['id'])->firstOrFail();

            Ticket::factory()->count($order['quantity'])->pending()->create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'ticket_category_id' => $category->id,
                'transaction' => $intent->id,
                'price' => $category->price,
            ]);
        });

        return [
            'client_secret' => $intent->client_secret,
            'transaction' => $intent->id,
        ];
    }

    public function preparePrice(Event $event, EventPreparePaymentRequest $request)
    {
        $this->abortIfNotJson();

        return [
            'price' => $request->getTotalAmountUnits()
        ];
    }

    public function setupEmail(EventSetupEmailRequest $request)
    {
        $this->abortIfNotJson();

        Session::put('event.payment.email', $request->getEmail());

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function verifyPayment(Event $event, Request $request)
    {
        $this->abortIfNotJson();

        $transaction = $request->get('order');

        /** @var StripeClient $stripe */
        $stripe = app(StripeService::class);

        $intent = $stripe->paymentIntents->retrieve($transaction);

        if ($intent->status === 'succeeded') {
            Ticket::where('transaction', $transaction)->update([
                'status' => TicketStatus::NonUsed,
            ]);
        }

        return [
            'status' => $intent->status,
            'redirect' => route('user.tickets')
        ];
    }

    public function retryPayment(Event $event, Request $request)
    {
        $this->abortIfNotJson();

        $transaction = $request->get('transaction');

        /** @var StripeClient $stripe */
        $stripe = app(StripeService::class);

        $intent = $stripe->paymentIntents->retrieve($transaction);

        if ($intent->status === "succeeded" || $intent->status === "canceled") {
            return [
                'redirect' => route('events.show', $event->slug),
            ];
        }

        $stripe->paymentIntents->cancel($transaction);

        Ticket::where('transaction', $transaction)->delete();

        return [
            'order' => $intent->metadata->order,
        ];
    }
}
