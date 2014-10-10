<?php

namespace Slackiss\Bundle\SlackwareBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendMailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('slackiss:slackware:sendmail')
            ->setDescription('sendmail');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $messageService = $container->get('slackiss_slackware.message');
        $messageService->mailMessages();
        $output->writeln('success');
    }

}