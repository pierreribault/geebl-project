<?php

namespace App\Actions\Events;

use App\Models\Event;
use Illuminate\Contracts\Pagination\Paginator;

class SearchAction
{
    public function search(string $query): Paginator
    {
        return Event::search($query)->paginate();
    }
}