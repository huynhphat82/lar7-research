<?php

namespace App\Common;

use App\User;
use App\Observers\UserObserver;

class Observer
{
    /**
     * Subscribe observables
     *
     * @return void
     */
    public static function subscribe()
    {
        // register events will be fired right after an event on model fired
        //User::observe(UserObserver::class);
    }
}
