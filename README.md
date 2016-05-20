# Laravel Event Worker
I needed a way to trigger async events with workers, delays, throttles, bla bla bla...

# minimum-stability: dev
Womb womb womb, this is alpha, for fun, but hey - it seems to work :D.

# Installation
Install with composer
~~~
composer require askedio/laravel-event-worker
~~~

Register the `provider` in `config/app.php`.
~~~
Askedio\EventWorker\Providers\LaravelEventWorkerServiceProvider::class,
~~~

Publish the config.
~~~
php artisan vendor:publish
~~~
Edit the config to adjust settings, bla bla.

Create some events, bla bla.

Run it.
~~~
php artisan events:serve
~~~