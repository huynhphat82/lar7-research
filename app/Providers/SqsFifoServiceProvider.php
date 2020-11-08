<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Implementations\SqsFifoConnector;

class SqsFifoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Register the service provider.
         *
         * @return void
         */
        $this->app->afterResolving('queue', function ($manager) {
            $this->registerSqsFifoConnector($manager);
        });
    }

    /**
     * Register the Amazon SQS FIFO queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    protected function registerSqsFifoConnector($manager)
    {
        $manager->addConnector('sqsfifo', function () {
            return new SqsFifoConnector;
        });
    }
}
