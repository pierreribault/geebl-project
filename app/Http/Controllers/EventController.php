<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use App\Actions\Events\SearchAction;
use App\Data\EventData;
use App\Http\Requests\EventPreparePaymentRequest;
use App\Http\Resources\EventResource;
use App\Http\Requests\Events\SearchRequest;
use App\Http\Requests\EventSetupEmailRequest;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
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

        /**
         * @todo: move to an action
         */

        // if (! $email) {
        //     return ValidationException::withMessages([
        //         'email' => ['Please provide an email address.'],
        //     ]);
        // }

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

        return [
            'client_secret' => $intent->client_secret,
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
}
