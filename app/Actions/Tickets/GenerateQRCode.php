<?php

namespace App\Actions\Tickets;

use App\Data\TicketData;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQRCode
{
    public function generate(TicketData $ticketData)
    {
        $ticket = Ticket::find($ticketData->id);

        $data = [
            'id' => $ticket->id,
            'name' => $ticket->user->name,
            'used' => $ticket->status
        ];


        return QrCode::size(200)
            ->backgroundColor(30, 33, 60)
            ->color(255, 255, 255)
            ->eyeColor(0, 90, 103, 216, 102, 126, 234)
            ->eyeColor(1, 90, 103, 216, 102, 126, 234)
            ->eyeColor(2, 90, 103, 216, 102, 126, 234)
            ->generate(json_encode($data))
            ->toHtml();
    }
}
