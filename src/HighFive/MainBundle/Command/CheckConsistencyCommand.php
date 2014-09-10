<?php

namespace HighFive\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckConsistencyCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('highfive:check-consistency')
            ->setDescription('Checks the database consistency.');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Checking the user name guessing</info>');

        /** @var $registry \Doctrine\Common\Persistence\ManagerRegistry */
        $registry = $this->getContainer()->get('doctrine');
        /** @var $guesser \HighFive\MainBundle\Util\NameGuesserInterface */
        $guesser = $this->getContainer()->get('high_five.util.name_guesser');

        $em = $registry->getManager();
        /** @var $users \HighFive\MainBundle\Entity\User[] */
        $users = $registry->getRepository('MainBundle:User')->findAll();

        while ($user = array_pop($users)) {
            if ('' !== $user->getFirstName() || '' !== $user->getLastName()) {
                $em->detach($user);
                continue;
            }
            if (OutputInterface::VERBOSITY_VERBOSE === $output->getVerbosity()) {
                $output->writeln(sprintf('    - <comment>Guessing for %s</comment>', $user->getEmail()));
            }
            $user->setFirstName(null)->setLastName(null);
            $guesser->guessNames($user);
        }

        $em->flush();
    }
}
