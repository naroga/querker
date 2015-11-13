<?php

namespace Querker\WorkerBundle\Worker;

use Querker\QueueBundle\Strategy\StrategyInterface;
use Querker\WorkerBundle\Provider\QueueProviderInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Class DaemonWorker
 * @package Querker\WorkerBundle\Worker
 */
class DaemonWorker implements WorkerInterface
{
    private $sigterm = false;
    private $name;
    /** @var QueueProviderInterface */
    private $provider;
    /** @var  StrategyInterface */
    private $locker;
    private $timeout;
    private $maxTries;
    private $tries = 0;

    /**
     * DaemonWorker constructor.
     *
     * @param QueueProviderInterface $provider
     * @param int $timeout
     * @param int $maxTries
     */
    public function __construct(
        QueueProviderInterface $provider,
        $timeout = 30,
        $maxTries = 3
    ) {
        $this->name = Uuid::uuid4()->toString();
        $this->provider = $provider;
        $this->timeout = $timeout;
    }

    public function getName()
    {
        return $this->name;
    }

    public function start()
    {

        $fHandler = fopen(__DIR__ . '/../../../../app/cache/' . $this->name . '.bin', 'w+');

        while (!$this->sigterm) {
            $process = $this->getNewProcess();
            ftruncate($fHandler);
            fwrite($fHandler, serialize($process));
            $this->process();
        }

        fclose($fHandler);

        $filesystem = new Filesystem();
        $filesystem->remove(__DIR__ . '/../../../../app/cache/' . $this->name . '.bin');
    }

    public function getNewProcess()
    {
        return $this->provider->getQueueManager()->extract();
    }

    private function process()
    {
        $this->tries++;
        $process = new Process(
            (new PhpExecutableFinder())->find() . realpath(__DIR__ . '/../../../../app/console') .
            ' querker:process ' . $this->name
        );
        $process->setTimeout($this->timeout);

        try {
            $process->run();
        } catch (ProcessTimedOutException $e) {
            if ($this->tries <= $this->maxTries) {
                $this->process();
            }
        }

        if (!$process->isSuccessful() && $this->tries <= $this->maxTries) {
            $this->process();
        }
    }

    public function stop()
    {
        $this->sigterm = true;
    }

    public function getStatus()
    {
        return ['working' => !$this->sigterm];
    }
}
