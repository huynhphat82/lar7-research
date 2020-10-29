<?php

namespace App\Providers;

use App\Binding\Binding;
use App\Common\Component;
use App\Common\Directive;
use App\Common\Observer;
use App\Services\Facades\AppLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Binding for IoC
        Binding::start($this->app);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set default length of column with string type in database
        Schema::defaultStringLength(191);

        // Log sql query
        AppLog::query();

        // Register directives
        Directive::register();

        // Register components
        Component::register();

        // Register observables
        Observer::subscribe();
    }
}
