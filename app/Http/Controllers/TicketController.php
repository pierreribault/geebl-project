<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Actions\Tickets\UseAction;
use App\Data\TicketData;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user()->getData()->include('tickets');

        return Inertia::render('Tickets/Show', [
            'transactions' => Auth::user()->getTransactionsDetails(),
        ]);
    }

    public function use(Ticket $ticket, UseAction $useAction)
    {
        $this->abortIfNotJson();

        $useAction->use($ticket);

        return TicketData::from($ticket)->include('user', 'event', 'category');
    }
}
