<?php

namespace HighFive\MainBundle\Model;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Mailer\MailerInterface;
use HighFive\MainBundle\Entity\Invitation;
use HighFive\MainBundle\Entity\User;
use HighFive\MainBundle\Event\InvitationEvent;
use HighFive\MainBundle\Util\NameGuesserInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InvitationManager
{
    private $mailer;
    private $registry;
    private $tokenGenerator;
    private $userManager;
    private $dispatcher;
    private $nameGuesser;

    /**
     * Delay before resending an invitation (in seconds)
     * @var integer
     */
    private $resentDelay;

    public function __construct(ManagerRegistry $registry, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, EventDispatcherInterface $dispatcher, NameGuesserInterface $nameGuesser, $resentDelay)
    {
        $this->mailer = $mailer;
        $this->registry = $registry;
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->dispatcher = $dispatcher;
        $this->nameGuesser = $nameGuesser;
        $this->resentDelay = $resentDelay;
    }

    /**
     * Sends the invitations for emails if they are not invited yet.
     *
     * @param array $emails
     * @param User  $referrer
     *
     * @return array
     */
    public function sendInvitations(array $emails, User $referrer)
    {
        $maxDate = new \DateTime(sprintf('- %s seconds', $this->resentDelay));

        $registeredEmails = array();
        $invitedEmails = array();
        $resentEmails = array();
        $sentEmails = array();

        $em = $this->registry->getManager();
        /** @var $invitationRepo \HighFive\MainBundle\Doctrine\Repository\InvitationRepository */
        $invitationRepo = $em->getRepository('MainBundle:Invitation');

        foreach ($emails as $email) {
            // Check if the user is already registered
            $existingUser = $this->userManager->findUserByEmail($email);
            if (null !== $existingUser) {
                if ($existingUser->isEnabled()) {
                    $registeredEmails[] = $email;
                    continue;
                }

                /** @var $existingInvitation Invitation */
                $existingInvitation = $invitationRepo->findOneBy(array('recipient' => $existingUser->getId()));
                $date = null === $existingInvitation->getResentAt() ? $existingInvitation->getCreatedAt() : $existingInvitation->getResentAt();
                if ($date > $maxDate) {
                    $invitedEmails[] = $email;
                    continue;
                }

                $resentEmails[] = $email;
                $existingInvitation->setResentAt(new \DateTime());
                $this->mailer->send($email, 'MainBundle:Mail:invitation.html.twig', array('invitation' => $existingInvitation, 'name' => $existingUser->getFirstName()));
                continue;
            }

            $sentEmails[] = $email;

            $user = new User();
            $user->setOrganization($referrer->getOrganization())
                ->setEmail($email)
                ->setEnabled(false)
                ->setPassword('');

            $this->nameGuesser->guessNames($user);

            $invitation = new Invitation();
            $invitation->setOrganization($referrer->getOrganization())
                ->setReferrer($referrer)
                ->setRecipient($user)
                ->setToken($this->tokenGenerator->generateToken())
                ->setCreatedAt(new \DateTime());

            $em->persist($user);
            $em->persist($invitation);
            $this->mailer->send($email, 'MainBundle:Mail:invitation.html.twig', array('invitation' => $invitation, 'name' => $user->getFirstName()));
        }

        $this->dispatcher->dispatch(HighFiveEvents::INVITATION_SENT, new InvitationEvent($referrer, $registeredEmails, $invitedEmails, $resentEmails, $sentEmails));

        $em->flush();

        return array(
            'registered_emails' => $registeredEmails,
            'invited_emails' => $invitedEmails,
            'resent_emails' => $resentEmails,
            'sent_emails' => $sentEmails,
        );
    }
}
