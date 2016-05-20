<?php

namespace Askedio\EventWorker\Providers;

use Askedio\EventWorker\Console\Commands\EventsCommand;
use Illuminate\Support\ServiceProvider;

class LaravelEventWorkerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\GrahamCampbell\Throttle\ThrottleServiceProvider::class);

        $this->app->singleton('command.events.serve', function () {
              return new EventsCommand();
        });

        $this->commands('command.events.serve');

        $this->mergeConfigFrom(__DIR__.'/../config/event-workers.php', 'workers');
    }

    /**
     * Register routes, translations, views and publishers.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
             __DIR__.'/../config/event-workers.php' => config_path('event-workers.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.events.serve'];
    }
}
