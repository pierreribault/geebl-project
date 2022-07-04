<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private Invoice $invoice)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $attachment = $this->invoice->getFirstMediaPath('invoice');

        $email = $this
            ->view('mails.invoices.created')->subject("Your invoice for {$this->invoice->product->name}")
            ->attach($attachment)
            ->with([
                'invoice' => $this->invoice,
            ]);

        return $email;
    }
}
