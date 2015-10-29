<?php

namespace Querker\QueueBundle\Strategy;

use Querker\QueueBundle\Exception\LockingException;
use Symfony\Component\Stopwatch\Stopwatch;

class FileLockStrategy implements StrategyInterface
{
    const MAX_WAIT_TIME = 3; //Maximum execution time, in seconds.

    /** @var string */
    private $file = null;

    /**
     * Class constructor
     *
     * @param string $file File path
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /** @inheritDoc */
    public function extract()
    {
        $watch = new Stopwatch();
        $watch->start('filelock');

        $fHandler = fopen($this->file, 'rw');
        while (!flock($fHandler, LOCK_EX)) {
            if ($watch->getEvent('filelock')->getDuration() >= (self::MAX_WAIT_TIME * 1000)) {
                throw new LockingException("Unable to get exclusive lock on file (" . $this->file . ").");
            }
        }

        $watch->stop('filelock');

        /** @var \SplPriorityQueue $queue */
        $queue = unserialize(fread($fHandler, filesize($this->file)));

        //Gets the process from the top of the queue.
        $process = $queue->extract();

        //Reserializes and writes the queue (without the first process).
        fwrite($fHandler, serialize($queue));

        //Releases lock and the file pointer.
        flock($fHandler, LOCK_UN);
        fclose($fHandler);

        return $process;
    }

    /** @inheritDoc */
    public function insert($process, \int $priority = 1)
    {
        $watch = new Stopwatch();
        $watch->start('filelock');

        $fHandler = fopen($this->file, 'rw');
        while (!flock($fHandler, LOCK_EX)) {
            if ($watch->getEvent('filelock')->getDuration() >= (self::MAX_WAIT_TIME * 1000)) {
                throw new LockingException("Unable to get exclusive lock on file (" . $this->file . ").");
            }
        }

        $watch->stop('filelock');

        /** @var \SplPriorityQueue $queue */
        $queue = unserialize(fread($fHandler, filesize($this->file)));

        //Gets the process from the top of the queue.
        $queue->insert($process, $priority);

        //Reserializes and writes the queue.
        fwrite($fHandler, serialize($queue));

        //Releases lock and the file pointer.
        flock($fHandler, LOCK_UN);
        fclose($fHandler);
    }
}
