<?php

namespace Querker\QueueBundle\Tests\Manager;

use GuzzleHttp\Psr7\Request;
use Querker\QueueBundle\Manager\QueueManager;
use Querker\QueueBundle\Process\RequestProcess;
use Querker\QueueBundle\Strategy\FileLockStrategy;

/**
 * Class QueueManagerTest
 * @package Querker\QueueBundle\Tests\Manager
 */
class QueueManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testQueueManager()
    {
        $manager = new QueueManager(new FileLockStrategy(__DIR__ . '/../../../../../app/cache/queue-test.bin'));
        $process = new RequestProcess(new Request('GET', '/'));
        $manager->insert($process);
        /** @var RequestProcess $extracted */
        $extracted = $manager->extract();
        $this->assertInstanceOf(RequestProcess::class, $extracted);
        $this->assertInstanceOf(Request::class, $extracted->getRequest());
        $this->assertEquals($extracted->getRequest()->getMethod(), 'GET');
        $this->assertEquals($extracted->getRequest()->getUri(), '/');
    }
}
