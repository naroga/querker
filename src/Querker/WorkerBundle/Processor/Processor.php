<?php

namespace Querker\WorkerBundle\Processor;

use Querker\QueueBundle\Process\ProcessInterface;
use Querker\WorkerBundle\Exception\InvalidProcessException;

/**
 * Class Processor
 * @package Querker\WorkerBundle\Processor
 */
class Processor
{
    /**
     * Returns the current saved process for a given worker.
     *
     * @param string $worker The worker name.
     * @return ProcessInterface
     * @throws InvalidProcessException If the worker file is not present.
     */
    public function getProcess($worker)
    {
        $fileName = realpath(__DIR__ . '/../../../../app/cache/' . $worker . '.bin');
        if (!file_exists($fileName)) {
            throw new InvalidProcessException("Unable to find process for worker " . $worker . '.');
        }
        $process = unserialize(file_get_contents($fileName));
        return $process;
    }
}
