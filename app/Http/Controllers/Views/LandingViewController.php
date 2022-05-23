<?php

namespace App\Http\Controllers\Views;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class LandingViewController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'countries' => [ // @todo: move to a service or something like that
                '🇫🇷 France' => [
                    'Paris',
                    'Angers',
                ],
            ]
        ]);
    }
}