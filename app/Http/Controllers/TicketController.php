<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Controllers\Controller;
use App\Actions\Tickets\UseAction;
use App\Data\TicketData;

class TicketController extends Controller
{
    public function use(Ticket $ticket, UseAction $useAction)
    {
        $this->abortIfNotJson();

        $useAction->use($ticket);

        return TicketData::from($ticket)->include('user', 'event', 'category');
    }
}