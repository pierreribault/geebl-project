<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use App\Actions\Events\SearchAction;
use App\Http\Resources\EventResource;
use App\Http\Requests\Events\SearchRequest;

class EventController
{
    public function search(SearchRequest $request, SearchAction $searcher)
    {
        return EventResource::collection(
            $searcher->search($request->getQuery())
        );
    }

    public function show(Event $event)
    {
        return Inertia::render('Event/Show', [
            'event' => $event
        ]);
    }
}
