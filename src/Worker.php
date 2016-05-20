<?php

namespace Askedio\EventWorker;

use GrahamCampbell\Throttle\Facades\Throttle;
use React\EventLoop\Factory;
use RogerWaters\ReactThreads\EventLoop\ForkableFactory;
use RogerWaters\ReactThreads\EventLoop\ForkableLoopInterface;
use RogerWaters\ReactThreads\ThreadBase;
use RogerWaters\ReactThreads\ThreadCommunicator;

class Worker extends ThreadBase
{
    protected $config;

    protected $loop;

    protected $iteration = 0;

    protected $factory;

    protected $balancer;

    protected $worker;

    public function __construct(ForkableLoopInterface $loop)
    {
        parent::__construct($loop);
        $this->loop = $loop;
    }

    public function init($worker, $config)
    {
        $this->config = $config;
        $this->worker = $worker;
        $this->factory = new ForkableFactory();

        return $this;
    }

    public function InitializeExternal(ThreadCommunicator $threadCommunicator)
    {
        $loop = $this->factory->create();
        $this->pool($loop);
        $loop->run();
    }

    private function pool($loop)
    {
        $this->balancer = (new Thread($loop))->CreateLoadBalancer(
            $loop,
            $this->config['threads']['min'],
            $this->config['threads']['max'],
            $this->config['threads']['timeout']
        );
        $this->balancer->init($this->config);
        $this->work($loop);
    }

    private function work($loop)
    {
        $loop->addPeriodicTimer($this->config['delay'], function () {
            $this->triggerEvent();
        });
    }

    private function canTriggerEvent()
    {
        return $this->balancer->getNumberOfThreads() < $this->balancer->getMaximumNumberOfThreads() || $this->balancer->getNumberOfThreadsLazy() > 0;
    }

    private function triggerEvent()
    {
        if ($this->canTriggerEvent() && !$this->isThrottled()) {
            $this->balancer->triggerEvent($this->iteration++);
        }
    }

    private function isThrottled()
    {
        if (!$config = $this->config['throttle']) {
            return false;
        }

        $throttle = explode(':', $config);

        return !Throttle::attempt(
            [
                'ip'    => gethostname(),
                'route' => $this->config['class'].$this->worker,
            ],
            $throttle[0],
            $throttle[1]
        );
    }
}
