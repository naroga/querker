<?php

namespace Querker\WorkerBundle\Tests\Provider;

use Querker\QueueBundle\Manager\QueueManager;
use Querker\QueueBundle\Strategy\FileLockStrategy;
use Querker\WorkerBundle\Provider\IntegratedQueueProvider;

/**
 * Class IntegratedQueueProviderTest
 * @package Querker\WorkerBundle\Tests\Provider
 */
class IntegratedQueueProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetQueueManager()
    {
        $provider = new IntegratedQueueProvider(new QueueManager(new FileLockStrategy('sampleFile.txt')));
        $this->assertInstanceOf(QueueManager::class, $provider->getQueueManager());
    }
}
