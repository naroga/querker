<?php

namespace Querker\QueueBundle\Tests\Strategy;

use Querker\QueueBundle\Strategy\FileLockStrategy;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FileLockStrategyTest
 * @package Querker\QueueBundle\Tests\Strategy
 */
class FileLockStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testIsAbleToGetLock()
    {
        $locker = new FileLockStrategy(__DIR__ . '/../../../../../app/cache/queue-test.bin');
        $locker->clear();
        $fileSize = filesize(__DIR__ . '/../../../../../app/cache/queue-test.bin');
        $rLocker = new \ReflectionObject($locker);
        $method = $rLocker->getMethod('getFile');
        $method->setAccessible(true);
        $file = $method->invoke($locker);
        $this->assertTrue(is_resource($file), true);
    }

    /**
     * @expectedException Querker\QueueBundle\Exception\LockingException
     */
    public function testIsFileLocked()
    {
        $locker = new FileLockStrategy(__DIR__ . '/../../../../../app/cache/queue-test.bin');
        $locker->clear();
        $rLocker = new \ReflectionObject($locker);
        $method = $rLocker->getMethod('getFile');
        $method->setAccessible(true);
        $file = $method->invoke($locker);
        $file2 = $method->invoke($locker);
    }

    public function testLockReleased()
    {
        $locker = new FileLockStrategy(__DIR__ . '/../../../../../app/cache/queue-test.bin');
        $locker->clear();
        $rLocker = new \ReflectionObject($locker);
        $method = $rLocker->getMethod('getFile');
        $method->setAccessible(true);
        $file = $method->invoke($locker);
        $this->assertTrue(is_resource($file), true);
        $method2 = $rLocker->getMethod('releaseFile');
        $method2->setAccessible(true);
        $method2->invokeArgs($locker, [$file]);
        $file = $method->invoke($locker);
        $this->assertTrue(is_resource($file), true);
    }

    public function testQueueInsert()
    {
        $locker = new FileLockStrategy(__DIR__ . '/../../../../../app/cache/queue-test.bin');
        $locker->clear();
        $locker->insert('test');
        $first = $locker->extract();
        $this->assertTrue($first != null);
    }

    public function testQueueClear()
    {
        $locker = new FileLockStrategy(__DIR__ . '/../../../../../app/cache/queue-test.bin');
        $locker->clear();
        $first = $locker->extract();
        $this->assertEquals($first, null);
        $locker->insert('test');
        $first = $locker->extract();
        $this->assertTrue($first != null);
        $locker->clear();
        $first = $locker->extract();
        $this->assertEquals($first, null);
    }

    public function testQueueRepoDeletedInRuntime()
    {
        $fs = new Filesystem();

        $locker = new FileLockStrategy(__DIR__ . '/../../../../../app/cache/queue-inexisting-file.bin');
        $locker->clear();

        $fs->remove(__DIR__ . '/../../../../../app/cache/queue-inexisting-file.bin');
        $this->assertEquals($locker->extract(), null);

        $fs->remove(__DIR__ . '/../../../../../app/cache/queue-inexisting-file.bin');
        $locker->insert('test');
        $this->assertEquals($locker->extract(), 'test');

        $locker->insert('test');
        $fs->remove(__DIR__ . '/../../../../../app/cache/queue-inexisting-file.bin');
        $this->assertEquals($locker->extract(), null);

    }
}
