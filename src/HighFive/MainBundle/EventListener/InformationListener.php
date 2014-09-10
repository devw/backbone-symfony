<?php

namespace HighFive\MainBundle\EventListener;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Event\UserEvent;
use HighFive\MainBundle\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listener responsible to send informative mails.
 */
class InformationListener implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            HighFiveEvents::REGISTRATION_REGISTER => 'onRegistrationComplete',
            HighFiveEvents::REGISTRATION_INVITED => 'onRegistrationComplete',
        );
    }

    public function onRegistrationComplete(UserEvent $event)
    {
        $user = $event->getUser();

        $this->mailer->send(
            $user->getEmail(),
            'MainBundle:Mail:presentation.html.twig',
            array('recipient' => $user)
        );
    }
}
