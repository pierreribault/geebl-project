<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePdfAction
{
    public function generate(Invoice $invoice)
    {
        $pdf = PDF::loadView('invoices.view-pdf', compact('invoice'));

        $invoice->addMediaFromStream(
            $pdf->stream()
        )
        ->usingFileName("{$invoice->id}.pdf")
        ->toMediaCollection('invoice', 'public');
    }
}
