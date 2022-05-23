<?php

namespace App\Observers;

use App\Jobs\GenerateTicket;
use App\Models\Slot;

class SlotObserver
{
    /**
     * Handle the Slot "created" event.
     *
     * @param  \App\Models\Slot  $slot
     * @return void
     */
    public function created(Slot $slot)
    {
        GenerateTicket::dispatch($slot);
    }

    /**
     * Handle the Slot "updated" event.
     *
     * @param  \App\Models\Slot  $slot
     * @return void
     */
    public function updated(Slot $slot)
    {
        //
    }

    /**
     * Handle the Slot "deleted" event.
     *
     * @param  \App\Models\Slot  $slot
     * @return void
     */
    public function deleted(Slot $slot)
    {
        //
    }

    /**
     * Handle the Slot "restored" event.
     *
     * @param  \App\Models\Slot  $slot
     * @return void
     */
    public function restored(Slot $slot)
    {
        //
    }

    /**
     * Handle the Slot "force deleted" event.
     *
     * @param  \App\Models\Slot  $slot
     * @return void
     */
    public function forceDeleted(Slot $slot)
    {
        //
    }
}
