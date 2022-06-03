<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class OrganizerController extends Controller
{
    public function validator()
    {
        return Inertia::render('Organizer/Validator');
    }
}
