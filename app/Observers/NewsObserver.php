<?php

namespace App\Observers;

use App\Enums\NewsStatus;
use App\Enums\TicketStatus;
use App\Jobs\SendNewsEmail;
use App\Models\News;
use App\Mail\UpadateNews;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NewsObserver
{
    /**
     * Handle the News "created" event.
     *
     * @param  \App\Models\News  $news
     * @return void
     */
    public function created(News $news)
    {
        //
    }

    /**
     * Handle the News "updated" event.
     *
     * @param  \App\Models\News  $news
     * @return void
     */
    public function updated(News $news)
    {
        //
    }

    public function saving(News $news)
    {
        if ($news->isDirty('status') && $news->status === NewsStatus::Published) {
            $news->date = now();

            User::participentOfEvent($news->event)->get()->unique('id')->each(
                fn ($user) => Mail::to($user)->queue(new UpadateNews($news))
            );
        }
    }

    /**
     * Handle the News "deleted" event.
     *
     * @param  \App\Models\News  $news
     * @return void
     */
    public function deleted(News $news)
    {
        //
    }

    /**
     * Handle the News "restored" event.
     *
     * @param  \App\Models\News  $news
     * @return void
     */
    public function restored(News $news)
    {
        //
    }

    /**
     * Handle the News "force deleted" event.
     *
     * @param  \App\Models\News  $news
     * @return void
     */
    public function forceDeleted(News $news)
    {
        //
    }
}
