<?php

namespace Querker\QueueBundle\Process;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestProcess
 * @package Querker\QueueBundle\Process
 */
class RequestProcess implements ProcessInterface
{
    /** @var Request */
    private $request;

    public function serialize()
    {
        return serialize($this->request);
    }

    public function unserialize($serialized)
    {
        $this->request = unserialize($serialized);
    }

    public function execute()
    {
        $request = new Request;
        $request->
    }
}
