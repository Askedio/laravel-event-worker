<?php

namespace Askedio\EventWorker\Console\Commands;

use Askedio\EventWorker\Worker;
use Illuminate\Console\Command;
use RogerWaters\ReactThreads\EventLoop\ForkableFactory;

class EventsCommand extends Command
{
    protected $signature = 'events:serve {--worker= : Worker Config Setting: event-workers.twitter}';

    protected $description = 'Start the Event Worker server.';

    protected $factory;

    public function __construct()
    {
        parent::__construct();
        $this->factory = new ForkableFactory;
    }

    public function handle()
    {
        $this->info('Starting Event Workers...');
        $this->start();
    }

    private function start()
    {
        $loop = $this->factory->create();
        $this->build($loop);
        $loop->run();
    }

    private function build($loop)
    {
        if ($name = $this->option('worker')) {
            $this->info('-- '.$name);

            return $this->workers($loop, config($name));
        }

        foreach (config('workers') as $name => $config) {
            $this->info('-- '.$name);
            $this->workers($loop, $config);
        }
    }

    private function workers($loop, $config)
    {
        for ($i = 0; $i < $config['workers']; $i++) {
            $this->work($i, $loop, $config);
        }
    }

    private function work($worker, $loop, $config)
    {
        try {
            (new Worker($loop))->init($worker, $config)->start();
        } catch (\Exception $exception) {
            app('log')->error($exception->getMessage());
        }
    }
}
