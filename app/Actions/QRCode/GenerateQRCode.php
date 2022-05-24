<?php

namespace App\Actions\QRCode;

use App\Models\Ticket;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQRCode
{
    public function generate(Ticket $ticket): string
    {
        $data = collect([
            'uuid' => $ticket->id,
            'purchaser' => $ticket->order->user->name,
            'birthday' => $ticket->order->user->birthday,
        ]);

        return QrCode::format('svg')
            ->size(300)
            ->gradient(90, 103, 216, 102, 126, 234, 'radial')
            ->generate($data->toJson())
            ->toHtml();
    }
}
