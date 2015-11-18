<?php

namespace Querker\QueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProcessCommand
 * @package Querker\QueueBundle\Command
 */
class ProcessCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('querker:process')
            ->addArgument('process', InputArgument::REQUIRED, 'Process name')
            ->setDescription('Processes a process, by its processor name.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->get('queue.processor')->getProcess($input->getArgument('process'))->execute();
    }
}
