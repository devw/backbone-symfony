<?php

namespace HighFive\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetPointsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('highfive:reset-points')
            ->setDescription('Resets the point balances of all users.');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $registry \Doctrine\Common\Persistence\ManagerRegistry */
        $registry = $this->getContainer()->get('doctrine');
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $registry->getManager();

        $dql = <<<DQL
UPDATE MainBundle:User u
SET u.givenPoints = :points,
    u.receivedPoints = :receivedPoints
DQL;

        $query = $em->createQuery($dql);
        $query->execute(array('points' => 0, 'receivedPoints' => 0));

        $output->writeln('<info>Reset the point balances for all users</info>');
    }
}
