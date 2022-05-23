<?php

namespace App\Actions\QRCode;

use App\Models\Ticket;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQRCode
{
    public function generate(Ticket $ticket): void
    {
        $data = collect([
            'uuid' => $ticket->uuid,
            'purchaser' => $ticket->slot->user->name,
            'birthday' => $ticket->slot->user->birthday,
        ]);

        $svg = QrCode::format('svg')
            ->size(300)
            ->gradient(90, 103, 216, 102, 126, 234, 'radial')
            ->generate($data->toJson())
            ->toHtml();

        $ticket
            ->addMediaFromString($svg)
            ->usingFileName($ticket->uuid . '.svg')
            ->toMediaCollection(Ticket::QRCODE_MEDIA_COLLECTION);
    }
}
