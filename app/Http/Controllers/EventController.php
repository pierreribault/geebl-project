<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use App\Actions\Events\SearchAction;
use App\Data\EventData;
use App\Http\Requests\EventPreparePaymentRequest;
use App\Http\Resources\EventResource;
use App\Http\Requests\Events\SearchRequest;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class EventController extends Controller
{
    public function search(SearchRequest $request, SearchAction $searcher)
    {
        return EventResource::collection(
            $searcher->search($request->getQuery())
        );
    }

    public function show(Event $event)
    {
        return Inertia::render('Event/Show', [
            'event' => EventData::fromModel($event)->include('kinds'),
            'pspPublicKey' => config('services.stripe.public_key'),
        ]);
    }

    public function preparePayment(EventPreparePaymentRequest $request)
    {
        $this->abortIfNotJson();

        /** @var StripeClient $stripe */
        $stripe = app(StripeService::class);
        $intent = $stripe->paymentIntents->create([
            'amount' => $request->getTotalAmountUnits(),
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return [
            'client_secret' => $intent->client_secret,
        ];
    }
}
