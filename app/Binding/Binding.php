<?php

namespace App\Binding;

class Binding
{
    static function start($app = null)
    {
        // Simple binding
        // app()->bind('App\Contracts\HttpContract', 'App\Implementations\Http');
        // app()->bind('App\Contracts\ApiContract', 'App\Implementations\Api');
        // app()->bind('App\Contracts\ApiContract', function ($app) {
        //     return new \App\Implementations\Api(new \App\Implementations\HttpNew());
        // });

        // Binding singleton
        app()->singleton('App\Contracts\HttpContract', 'App\Implementations\Http');
        // app()->singleton('App\Contracts\ApiContract', 'App\Implementations\Api');
        // app()->singleton('App\Contracts\ApiContract', function ($app) {
        //     return new \App\Implementations\Api(new \App\Implementations\HttpNew());
        // });

        // Binding instance
        app()->instance(
            'App\Contracts\ApiContract',
            new \App\Implementations\Api(new \App\Implementations\HttpNew())
        );

        // Binding by context
        app() // if it is ApiController, we use HttpNew
            ->when(\App\Http\Controllers\ApiController::class)
            ->needs(\App\Contracts\ApiContract::class)
            ->give(function () {
                return new \App\Implementations\Api(
                    new \App\Implementations\HttpNew() //<---
                );
            });

        app() // if it is ApiNewController, we use Http
            ->when(\App\Http\Controllers\ApiNewController::class)
            ->needs(\App\Contracts\ApiContract::class)
            ->give(function () {
                return new \App\Implementations\Api(
                    new \App\Implementations\Http() //<---
                );
            });

        // Binding Primitives
        // (inject variable $primitives with value as [1, 2, 3, 5, 7]
        // into contructor method)
        app()
            ->when(\App\Http\Controllers\ApiController::class)
            ->needs('$primitives')
            ->give([1, 2, 3, 5, 7]);

        // Binding typed variadics & tagging dependencies
        $reports = [
            \App\Implementations\SpeedReport::class,
            \App\Implementations\MemoryReport::class,
        ];
        foreach ($reports as $ReportClass) {
            app()->bind($ReportClass, function () use ($ReportClass) {
                return new $ReportClass();
            });
        }
        app()->tag($reports, 'reports');
        app()->bind(\App\Implementations\Reporter:: class, function ($app) {
            return new \App\Implementations\Reporter($app->tagged('reports'));
        });
        // app()
        //     ->when(\App\Implementations\Reporter:: class)
        //     ->needs(\App\Implementations\Report::class)
        //     ->giveTagged('reports');
        // app()
        //     ->when(\App\Implementations\Reporter::class)
        //     ->needs('$reports')
        //     ->giveTagged('reports');


        // Binding Typed Variadics

        // Events of container
        app()->resolving(function ($obj, $app) {
            // called when container resloves object of any types
        });

        app()->resolving(\App\Contracts\ApiContract::class, function ($api, $app) {
            // called when container resolves objects of type 'App\Contracts\ApiContract'
        });

        app()->singleton(\App\Contracts\ExporterContract::class, \App\Implementations\ExporterExcel::class);

        app()->singleton(\App\Contracts\Queue::class, function ($app) {
            return new \App\Implementations\AwsQueue(env('SQS_QUEUE'), new \Aws\Sqs\SqsClient(config('aws')));
        });
    }
}
