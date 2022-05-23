<?php

namespace App\Http\Controllers\Webhooks;

use Stripe\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $event = Event::constructFrom($request->all());
        } catch(\UnexpectedValueException $e) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        Log::info("Stripe webhook received", [
            'event' => $event,
        ]);

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                //dd($paymentIntent);
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }
}