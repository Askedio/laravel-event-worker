<?php

namespace Askedio\EventWorker;

use RogerWaters\ReactThreads\EventLoop\ForkableLoopInterface;
use RogerWaters\ReactThreads\ThreadBase;
use RogerWaters\ReactThreads\ThreadCommunicator;

class Thread extends ThreadBase
{
    protected $config;

    public function __construct(ForkableLoopInterface $loop)
    {
        parent::__construct($loop);
    }

    public function init($config)
    {
        $this->config = $config;
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
