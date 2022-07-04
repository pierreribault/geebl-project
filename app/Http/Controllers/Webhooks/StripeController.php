<?php

namespace App\Http\Controllers\Webhooks;

use App\Events\ChargeSucceeded;
use Stripe\Event as StripeEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $event = StripeEvent::constructFrom($request->all());
        } catch (\UnexpectedValueException $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        Log::info("Stripe webhook received", [
            'event' => $event,
        ]);

        switch ($event->type) {
            case 'charge.succeeded':
                $charge = $event->data->object;
                Event::dispatch(new ChargeSucceeded($charge));
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
