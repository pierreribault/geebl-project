<?php

namespace App\Http\Controllers;

use App\Actions\Tickets\RefundAction;
use App\Models\Ticket;
use App\Actions\Tickets\UseAction;
use App\Data\TicketData;
use App\Enums\TicketStatus;
use Illuminate\Http\Request;
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

    public function refundOrder(Request $request)
    {
        $this->abortIfNotJson();
        $transaction = $request->get('transaction');

        $tickets = Ticket::query()
            ->where('transaction', $transaction)
            ->where('status', TicketStatus::NonUsed->value)
            ->where('user_id', Auth::id())
            ->get();

        $action = new RefundAction();
        $tickets->each(fn ($ticket) => $action->refund($ticket));

        return [
            'status' => 'refunded',
        ];
    }
}
