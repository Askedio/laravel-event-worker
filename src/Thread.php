<?php

namespace Askedio\EventWorker;

use RogerWaters\ReactThreads\ThreadBase;
use React\EventLoop\Factory;
use RogerWaters\ReactThreads\ThreadCommunicator;
use RogerWaters\ReactThreads\EventLoop\ForkableLoopInterface;

class Thread extends ThreadBase
{
    protected $config;

    protected $console;

    public function __construct(ForkableLoopInterface $loop)
    {
        parent::__construct($loop);
    }

    public function init($config, $console)
    {
        $this->config = $config;
        $this->console = $console;
    }

    public function InitializeExternal(ThreadCommunicator $loop)
    {
        //
    }

    public function triggerEvent($iteration)
    {
        event(new $this->config['class']($iteration));
    }
}
