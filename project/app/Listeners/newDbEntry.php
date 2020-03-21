<?php

namespace App\Listeners;

use App\Events\TaskStarted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class newDbEntry
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
     * @param  TaskStarted  $event
     * @return void
     */
    public function handle(TaskStarted $event)
    {
        //
    }
}
