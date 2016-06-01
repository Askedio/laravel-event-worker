# Laravel Event Worker
Trigger Laravel Events with mulitple workers, threads, delays & throttles.

# minimum-stability: dev
This package uses [react-thread-pool](https://github.com/RogerWaters/react-thread-pool) that is a dev release and this package itself is very experimental.

# Requirements
* Laravel 5.2.*
* PHP >= 5.4, only tested on 7.
* pcntl
* Linux/Unix

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


# Usage
The default config uses the `App\Events\FetchTwitterEvent` event, create it or edit `configs/event-workers.php` to use your events.


Create the event.
~~~
php artisan make:event FetchTwitter
~~~

Edit `app/Events/FetchTwitterEvent.php` and make it do something, like..
~~~
public function __construct($iteration)
{
    echo "Hello world.".PHP_EOL;
}
~~~
Certainly you'll put stuff in the listener and make a proper event, but you get the idea.


Run it.
~~~
php artisan events:serve
~~~

# Configuration
In `configs/event-workers.php` you define the events you want to run.

~~~
return [
  'twitter' => [              // name
      'delay'    => 1,        // delay between creating threads
      'throttle' => '1:1',    // throttle for creating threads
      'workers'  => 1,        // number of workers to create
      'threads'  => [
          'min'     => 1,     // minium threads per worker
          'max'     => 3,     // maximum threads per worker
          'timeout' => 30,    // thread time out
      ],
      'class' => \App\Events\FetchTwitterEvent::class, // your event class
  ],
];
~~~

# Testing
Ya right. I aint got time for that.

# Contributing
That'd be swell. Send a PR.
