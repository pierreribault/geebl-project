<?php

use App\Http\Controllers\Webhooks\StripeController;
use Illuminate\Support\Facades\Route;

Route::post('stripe', StripeController::class);
