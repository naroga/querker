<?php

namespace Querker\QueueBundle\Process;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * Class RequestProcess
 * @package Querker\QueueBundle\Process
 */
class RequestProcess implements ProcessInterface
{
    /** @var Request */
    private $request;

    private $output;

    private $error;

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
        ]);

        try {
            $response = $client->request($this->request->getMethod(), $this->request->getUri(), [
                'body' => $this->request->getBody()->getContents(),
            ]);
        } catch (ClientException $e) {
            $this->error = [
                'status' => $e->getResponse()->getStatusCode(),
                'content' => $e->getResponse()->getBody()->getContents()
            ];
            return false;
        }

        $this->output = $response->getBody()->getContents();
        return true;
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

    public function getOutput()
    {
        return $this->output;
    }

    public function getError()
    {
        return $this->error;
    }
}
