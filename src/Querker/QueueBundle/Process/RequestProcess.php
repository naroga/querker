<?php

namespace Querker\QueueBundle\Process;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Class RequestProcess
 * @package Querker\QueueBundle\Process
 */
class RequestProcess implements ProcessInterface
{
    /** @var Request */
    private $request;

    /**
     * RequestProcess constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize($this->request);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $this->request = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $client = new Client([
            'headers' => $this->request->getHeaders(),
            'base_uri' => $this->request->getUri(),
        ]);

        $client->request($this->request->getMethod(), [
            'body' => $this->request->getBody(),
        ]);
    }

    /**
     * Get request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
