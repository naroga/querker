<?php

namespace Querker\QueueBundle\Process;

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProcessFactory
 * @package Querker\QueueBundle\Process
 */
class ProcessFactory
{
    /**
     * @param $type
     * @param Request $request
     * @return null|RequestProcess
     */
    public static function build($type, Request $request)
    {

        switch ($type) {
            case 'request':
                $requestObject = unserialize($request->getContent());
                return new RequestProcess($requestObject);
            default:
                return null;
        }
    }
}
