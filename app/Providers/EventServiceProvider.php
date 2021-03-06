<?php

namespace App\Providers;

use App\Models\News;
use App\Events\ChargeSucceeded;
use App\Observers\NewsObserver;
use App\Events\TicketTransferred;
use App\Listeners\OnChargeSucceeded;
use Illuminate\Auth\Events\Registered;
use App\Listeners\NotifyTicketTransferred;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ChargeSucceeded::class => [
            OnChargeSucceeded::class,
        ],

        TicketTransferred::class => [
            NotifyTicketTransferred::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        News::observe(NewsObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
