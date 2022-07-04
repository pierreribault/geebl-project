<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePdfAction
{
    public function generate(Ticket $ticket)
    {
        $pdf = PDF::loadView('tickets.view-pdf', compact('ticket'));

        $ticket->addMediaFromStream(
            $pdf->stream()
        )
        ->usingFileName("{$ticket->id}.pdf")
        ->toMediaCollection('invoice', 'public');
    }
}
