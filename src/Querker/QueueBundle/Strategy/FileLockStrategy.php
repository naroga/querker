<?php

namespace Querker\QueueBundle\Strategy;

use Querker\PriorityQueue\PriorityQueue;
use Querker\QueueBundle\Exception\LockingException;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class FileLockStrategy
 * @package Querker\QueueBundle\Strategy
 */
class FileLockStrategy implements StrategyInterface
{
    const MAX_WAIT_TIME = 3; //Maximum wait time, in seconds.

    /** @var string */
    private $file = null;

    /**
     * Class constructor
     *
     * @param string $file          File path
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /** @inheritDoc */
    public function clear()
    {
        $queue = new PriorityQueue();

        $fHandler = $this->getFile();
        fwrite($fHandler, serialize($queue));
        $this->releaseFile($fHandler);
    }

    /** @inheritDoc */
    public function extract()
    {
        $fHandler = $this->getFile();

        $queue = unserialize(file_get_contents($this->file));
        $process = $queue->extract();

        //Reserializes and writes the queue (without the first process).
        ftruncate($fHandler, 0);
        rewind($fHandler);
        fwrite($fHandler, serialize($queue));

        $this->releaseFile($fHandler);

        return $process;
    }

    /** @inheritDoc */
    public function insert($process, $priority = 1)
    {
        $fHandler = $this->getFile();

        $queue = unserialize(file_get_contents($this->file));
        $queue->insert($process, $priority);

        $serializedData = serialize($queue);

        ftruncate($fHandler, 0);
        rewind($fHandler);

        $return = fwrite($fHandler, $serializedData);
        $this->releaseFile($fHandler);
    }

    /**
     * Locks and gets a file handler.
     *
     * @return resource             The file handler.
     * @throws LockingException
     */
    private function getFile()
    {
        $init = false;

        if (!file_exists($this->file)) {
            $init = true;
            touch($this->file);
        }

        $fHandler = fopen($this->file, 'r+');
        $block = false;

        $stopWatch = new Stopwatch();
        $stopWatch->start('querker.filelock.getfile');

        $locked = false;

        do {
            if (!flock($fHandler, LOCK_EX | LOCK_NB, $block)) {
                if ($block) {
                    if ($stopWatch->getEvent('querker.filelock.getfile')->getDuration() <= self::MAX_WAIT_TIME * 1000) {
                        sleep(0.1);
                    } else {
                        throw new LockingException("Unable to get exclusive lock on file (" . $this->file . ").");
                    }
                }
            } else {
                $locked = true;
            }
        } while (!$locked);

        if ($init) {
            fwrite($fHandler, serialize(new PriorityQueue()));
        }

        $stopWatch->stop('querker.filelock.getfile');

        return $fHandler;
    }

    /**
     * Releases a lock and closes the file handler.
     *
     * @param resource $fHandler     The file handler
     */
    private function releaseFile($fHandler)
    {
        //Releases lock and the file pointer.
        flock($fHandler, LOCK_UN);
        fclose($fHandler);
    }
}
