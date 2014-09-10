<?php

namespace HighFive\MainBundle\Notification;

use Doctrine\Common\Persistence\ManagerRegistry;
use HighFive\MainBundle\Entity\Organization;
use HighFive\MainBundle\Mailer\MailerInterface;

class SummarySender
{
    private $mailer;
    private $registry;
    private $senderAddress;

    public function __construct(MailerInterface $mailer, ManagerRegistry $registry, $senderAddress)
    {
        $this->mailer = $mailer;
        $this->registry = $registry;
        $this->senderAddress = $senderAddress;
    }

    public function sendSummary(Organization $organization)
    {
        /** @var $messageRepository \HighFive\MainBundle\Doctrine\Repository\MessageRepository */
        $messageRepository = $this->registry->getRepository('MainBundle:Message');
        /** @var $userRepository \HighFive\MainBundle\Doctrine\Repository\UserRepository */
        $userRepository = $this->registry->getRepository('MainBundle:User');

        $messages = $messageRepository->getTopMessages($organization);
        /** @var $users \HighFive\MainBundle\Entity\User[] */
        $users = $userRepository->findByOrganization($organization->getId());
        $board = $userRepository->getMonthlyBoard($organization, 5);

        foreach ($users as $user) {
            $this->mailer->send(
                $user->getEmail(),
                'MainBundle:Mail:summary.html.twig',
                array('organization' => $organization, 'top_messages' => $messages, 'recipient' => $user, 'board' => $board),
                $this->senderAddress
            );
        }
    }
}
