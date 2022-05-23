<?php

namespace App\Http\Controllers\Views;

use App\Enums\Carousels;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarouselRequest;
use App\Http\Resources\CarouselResource;
use App\Models\Event;

class LandingViewController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome');
    }
}