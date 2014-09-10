<?php

namespace HighFive\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendSummaryCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('highfive:send-summary')
            ->setDescription('Sends the weekly summary.');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Sending the summary emails</info>');

        /** @var $registry \Doctrine\Common\Persistence\ManagerRegistry */
        $registry = $this->getContainer()->get('doctrine');
        /** @var $summarySender \HighFive\MainBundle\Notification\SummarySender */
        $summarySender = $this->getContainer()->get('high_five.notification.summary_sender');

        /** @var $organizations \HighFive\MainBundle\Entity\Organization[] */
        $organizations = $registry->getRepository('MainBundle:Organization')->findAll();

        foreach ($organizations as $organization) {
            if (OutputInterface::VERBOSITY_VERBOSE === $output->getVerbosity()) {
                $output->writeln(sprintf('    - <comment>%s</comment>', $organization->getName()));
            }

            $summarySender->sendSummary($organization);
        }
    }
}
