<?php

namespace App\Nova\Actions;

use App\Actions\Tickets\RefundAction;
use App\Models\Ticket;
use App\Services\StripeService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Actions\DestructiveAction;

class RefundTicket extends DestructiveAction
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var RefundAction $action */
        $action = app(RefundAction::class);

        $tickets = $models->filter(fn ($model) => $model instanceof Ticket);

        /** @var Ticket $ticket */
        foreach ($tickets as $ticket) {
            $action->refund($ticket);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
