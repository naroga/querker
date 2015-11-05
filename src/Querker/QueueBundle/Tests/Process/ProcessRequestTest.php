<?php

namespace Querker\QueueBundle\Tests\Process;

use GuzzleHttp\Psr7\Request;
use Querker\QueueBundle\Process\RequestProcess;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ProcessRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestExecute()
    {
        $process = new Process((new PhpExecutableFinder())->find() . ' -S 127.0.0.1:1337 -t ' . realpath(__DIR__));
        $process->start();
        sleep(1);
        $request = new RequestProcess(new Request('GET', 'http://127.0.0.1:1337/SampleRequest.php'));
        $this->assertEquals($request->execute(), true);
        $this->assertEquals($request->getOutput(), 'Hello');
        $process->stop();
    }

    public function testRequest404()
    {
        $process = new Process((new PhpExecutableFinder())->find() . ' -S 127.0.0.1:1337 -t ' . realpath(__DIR__));
        $process->start();
        sleep(1);
        $request = new RequestProcess(new Request('GET', 'http://127.0.0.1:1337/InexistingRequest.php'));
        $this->assertEquals($request->execute(), false);
        $this->assertEquals($request->getError()['status'], 404);
        $process->stop();
    }
}
