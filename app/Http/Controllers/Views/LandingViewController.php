<?php

namespace App\Http\Controllers\Views;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class LandingViewController extends Controller
{
    public function home()
    {
        return Inertia::render('Home');
    }

    public function terms()
    {
        return Inertia::render('Terms');
    }

    public function privacy()
    {
        return Inertia::render('Privacy');
    }
}
