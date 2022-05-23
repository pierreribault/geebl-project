<?php

namespace App\Http\Controllers;

use App\Actions\Tickets\CaptureAction;
use Inertia\Inertia;

class OrganizerController extends Controller
{
    public function validator()
    {
        return Inertia::render('Organizer/Validator');
    }

    /**
     * @todo
     *
     * @param CaptureAction $action
     * @return void
     */
    public function capture(CaptureAction $action)
    {
        return $action->capture();
    }
}
