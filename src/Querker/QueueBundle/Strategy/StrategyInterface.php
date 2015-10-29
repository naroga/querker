<?php

namespace Querker\QueueBundle\Strategy;

/**
 * Interface StrategyInterface
 * @package Querker\QueueBundle\Strategy
 */
interface StrategyInterface
{
    /**
     * Clears the queue.
     */
    public function clear();

    /**
     * Gets the process in the top of the queue.
     *
     * @return object           The process.
     */
    public function extract();

    /**
     * Adds a process to the queue.
     *
     * @param object $process   The process to be added to the queue.
     * @param int $priority     The priority, an integer (higher number represents a higher priority)
     * @return bool
     */
    public function insert($process, $priority = 1);
}
