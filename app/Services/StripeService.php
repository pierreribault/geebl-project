<?php

namespace App\Services;

use Stripe\StripeClient;

class StripeService
{
    public StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.secret_key'));
    }
}
