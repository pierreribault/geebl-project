<?php

namespace App\Providers;

use Laravel\Scout\Builder;
use Illuminate\Support\ServiceProvider;

class MeiliSearchProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // custom way to get an engine client access and perform indexes operations
        Builder::macro('client', function () {
            return $this->engine();
        });
    }
}
