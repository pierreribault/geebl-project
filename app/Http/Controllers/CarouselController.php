<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Enums\Carousels;
use App\Http\Resources\EventResource;
use App\Http\Resources\CarouselResource;
use Illuminate\Http\Request;

class CarouselController extends Controller
{
    public function index()
    {
        $this->abortIfNotJson();

        return Carousels::getValues();
    }

    public function show(string $key)
    {
        $this->abortIfNotJson();

        return new CarouselResource([
            'key' => $key,
            'label' => Carousels::label($key),
            'data' => EventResource::collection(
                Event::carousel($key)->take(4)->get()
            ),
        ]);
    }
}
