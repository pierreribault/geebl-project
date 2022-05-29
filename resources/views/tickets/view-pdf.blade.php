<div class="h-90 max-h-90 w-50 max-w-50">
    <img src=""/>
    <h1>{{ $ticket->category->name }}</h1>
    <p>{{ $ticket->event->start_at->format('Y-m-d') }} - {{ $ticket->event->end_at->format('Y-m-d') }}
    <img src="" />
    <p>{{ $ticket->id }} - {{ $ticket->price }}</p>
</div>