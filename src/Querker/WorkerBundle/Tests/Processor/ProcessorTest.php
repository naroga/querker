<?php

namespace Querker\WorkerBundle\Tests\Processor;

use GuzzleHttp\Psr7\Request;
use Querker\QueueBundle\Process\ProcessInterface;
use Querker\QueueBundle\Process\RequestProcess;
use Querker\WorkerBundle\Processor\Processor;

/**
 * Class ProcessorTest
 * @package Querker\WorkerBundle\Tests\Processor
 */
class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Querker\WorkerBundle\Exception\InvalidProcessException
     */
    public function testGetProcessWithInvalidWorker()
    {
        $processor = new Processor();
        $processor->getProcess("ThisWorkerDoesNotExist!");
    }

    public function testGetProcess()
    {
        $processor = new Processor;
        $process = new RequestProcess(new Request('GET', '/'));
        $serializedProcess = serialize($process);
        $fHandle = fopen(__DIR__ . '/../../../../../app/cache/test-process.bin', 'w+');
        fwrite($fHandle, $serializedProcess);
        $recuperatedProcess = $processor->getProcess('test-process');
        $this->assertInstanceOf(ProcessInterface::class, $recuperatedProcess);
        $this->assertInstanceOf(RequestProcess::class, $recuperatedProcess);
    }
}
