<?php

namespace App\Providers;

use App\User;
use App\Binding\Binding;
use App\Observers\UserObserver;
use App\Services\Facades\AppLog;
use Illuminate\Support\Facades\Blade;
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
        Binding::start($this->app);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        AppLog::query();

        //
        Blade::directive('dd', function ($value) {
            return "<?php dd($value); ?>";
        });

        Blade::directive('vardump', function ($value) {
            return "<?php var_dump($value); ?>";
        });
        Blade::directive('var_dump', function ($value) {
            return "<?php var_dump($value); ?>";
        });
        Blade::directive('form', function ($args) {
            $method = 'GET';
            $hasMethod = false;
            $tagMethodHidden = '';
            $attrs = array_reduce(explode(',', $args), function ($carry, $param) use (&$method, &$hasMethod) {
                $split = explode('=', trim($param));
                if (strtolower($split[0]) === 'method') {
                    $hasMethod = true;
                    $method = count($split) > 1 ? trim(strtoupper(trim($split[1])), '\',"'): $method;
                    return $carry;
                }
                $carry .= ' '.implode('=', array_map(function ($p) { return trim($p); }, $split));
                return $carry;
            }, '');
            if (!$hasMethod) {
                $attrs .= ' method="GET"';
            } else if (in_array($method, ['PUT', 'DELETE'])) {
                $attrs .= ' method="POST"';
                $tagMethodHidden = '<input type="hidden" name="_method" value="'.$method.'">';
            } else {
                $attrs .= ' method="POST"';
            }
            return '
                <form '.$attrs.'>'.
                $tagMethodHidden.'
                <input type="hidden" name="_token" value="'.csrf_token().'">
            ';
        });
        Blade::directive('endform', function ($value) {
            return "</form>";
        });

        // Register components here
        // Blade::component('alert', \App\View\Components\Alert::class);

        // register events will be fired right after an event on model fired
        // User::observe(UserObserver::class);
    }
}
