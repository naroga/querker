<?php

namespace Querker\QueueBundle\Tests\Process;

use Querker\QueueBundle\Process\ProcessFactory;
use Querker\QueueBundle\Process\RequestProcess;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProcessFactoryTest
 * @package Querker\QueueBundle\Tests\Process
 */
class ProcessFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateRequestProcess()
    {
        $process = ProcessFactory::build(
            'request',
            Request::create('/', 'POST', [], [], [], [], serialize(new \GuzzleHttp\Psr7\Request(
                'GET',
                '/'
            )))
        );

        $this->assertInstanceOf(RequestProcess::class, $process);
        $this->assertInstanceOf(\GuzzleHttp\Psr7\Request::class, $process->getRequest());
        $this->assertEquals($process->getRequest()->getMethod(), 'GET');
        $this->assertEquals($process->getRequest()->getUri(), '/');
    }

    public function testCreateDefaultProcess()
    {
        $process = ProcessFactory::build(
            'inexistentprocesstype',
            new Request()
        );

        $this->assertEquals($process, null);
    }
}
