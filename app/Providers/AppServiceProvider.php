<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\News;
use App\Models\Ticket;
use App\Observers\InvoiceObserver;
use App\Observers\NewsObserver;
use App\Observers\TicketObserver;
use App\Services\StripeService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(StripeService::class, function ($app) {
            return (new StripeService(
                config('services.stripe.secret_key')
            ))->client;
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Invoice::observe(InvoiceObserver::class);

        Ticket::observe(TicketObserver::class);

        News::observe(NewsObserver::class);
    }
}
