<?php

namespace Querker\QueueBundle\Tests;

use Querker\PriorityQueue\PriorityQueue;

/**
 * Class PriorityQueueTest
 * @package Querker\QueueBundle\Tests
 */
class PriorityQueueTest extends \PHPUnit_Framework_TestCase
{
    public function testQueue()
    {
        $priorityQueue = new PriorityQueue();
        $priorityQueue->insert('test1', 1);
        $priorityQueue->insert('test2', 2);
        $priorityQueue->insert('test3', 1);
        $this->assertEquals($priorityQueue->count(), 3);
        $this->assertEquals($priorityQueue->extract(), 'test2');
        $this->assertEquals($priorityQueue->count(), 2);
        $this->assertEquals($priorityQueue->extract(), 'test1');
        $this->assertEquals($priorityQueue->count(), 1);
        $this->assertEquals($priorityQueue->extract(), 'test3');
        $this->assertEquals($priorityQueue->count(), 0);
    }
}
