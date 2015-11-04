<?php

namespace Querker\QueueBundle\Process;

/**
 * Interface ProcessInterface
 * @package Querker\QueueBundle\Process
 */
interface ProcessInterface extends \Serializable
{
    public function execute();
}