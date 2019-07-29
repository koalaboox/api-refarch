<?php

namespace App\Listeners;

use App\Events\InvalidToken;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveTokenFromUser
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
     * @param  InvalidToken  $event
     * @return void
     */
    public function handle(InvalidToken $event)
    {
        if ($event->user instanceof User) {
            $event->user->clearTokenAttributes();
        }
    }
}
