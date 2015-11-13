<?php

namespace Querker\WorkerBundle\Provider;

use Querker\QueueBundle\Manager\QueueManager;

/**
 * Class IntegratedQueueProvider
 * @package Querker\WorkerBundle\Provider
 */
class IntegratedQueueProvider implements QueueProviderInterface
{
    /** @var QueueManager */
    private $queueManager;

    public function getQueueManager()
    {
        return $this->queueManager;
    }
}
