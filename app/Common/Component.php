<?php

namespace App\Common;

use Illuminate\Support\Facades\Blade;

class Component
{
    /**
     * Register components
     *
     * @return void
     */
    public static function register()
    {
        Blade::component('alert', \App\View\Components\Alert::class);
    }
}
