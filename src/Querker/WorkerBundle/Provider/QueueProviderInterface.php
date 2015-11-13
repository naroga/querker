<?php

namespace Querker\WorkerBundle\Provider;

/**
 * Interface QueueProviderInterface
 * @package Querker\WorkerBundle\Provider
 */
interface QueueProviderInterface
{
    public function getQueueManager();
}
