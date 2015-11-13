<?php

namespace Querker\WorkerBundle\Worker;

/**
 * Class WorkerInterface
 * @package Querker\WorkerBundle\Worker
 */
interface WorkerInterface
{
    public function getName();
    public function getStatus();
    public function start();
    public function stop();
}
