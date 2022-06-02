<?php

namespace App\Services;

use App\Models\City;

class LocalizationService
{
    public function getCityFromIp(string $ip = null): City
    {
        if (! $ip) {
            $ip = request()->ip();
        }

        $geo = geoip($ip);

        return City::where('name', $geo->city)->first() ?? City::first();
    }
}