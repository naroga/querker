<?php

namespace Querker\QueueBundle\Manager;

use Querker\QueueBundle\Process\ProcessInterface;
use Querker\QueueBundle\Strategy\StrategyInterface;

/**
 * Class QueueManager
 * @package Querker\QueueBundle\Manager
 */
class QueueManager
{
    /** @var StrategyInterface */
    private $queue;

    /**
     * Class constructor
     *
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy)
    {
        $this->queue = $strategy;
    }

    /**
     * Gets the process in the top of the queue.
     *
     * @return object           The process.
     */
    public function extract()
    {
        return unserialize($this->queue->extract());
    }

    /**
     * Adds a process to the queue.
     *
     * @param ProcessInterface $process     The process to be added to the queue.
     * @param int $priority                 The priority, an integer (higher number represents a higher priority)
     */
    public function insert(ProcessInterface $process, \int $priority = 1)
    {
        $this->queue->insert(serialize($process), $priority);
    }
}
