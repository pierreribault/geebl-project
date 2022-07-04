<?php

namespace App\Http\Middleware;

use App\Data\CityData;
use App\Models\Country;
use Inertia\Middleware;
use App\Data\CountryData;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Services\LocalizationService;
use App\Services\LocalizationServices;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'ziggy' => function () {
                return (new Ziggy)->toArray();
            },

            'localizations' => $this->localizations(),
        ]);
    }

    private function localizations()
    {
        return [
            'current' => CityData::fromModel(app(LocalizationService::class)->getCityFromIp())->include('event'),
            'all' => CountryData::collection(Country::all())->include('cities.event')->toArray()
        ];
    }
}
