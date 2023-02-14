<?php

namespace App\Listeners;

use App\Events\PodcastProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;

class SendPodcastNotification
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PodcastProcessed  $event
     * @return void
     */
    public function handle(PodcastProcessed $event): void
    {
        //throw new \Exception('chto to poshlo ne tak', 3500);

        logger()->warning(printf("I am the %s event lisnerer!", "'podcast'"));
    }

    public function failed($event, $exception): void
    {
        logger()->error($exception->message);
    }
}
