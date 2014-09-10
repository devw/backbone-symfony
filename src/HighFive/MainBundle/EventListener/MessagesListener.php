<?php

namespace HighFive\MainBundle\EventListener;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Entity\Message;
use HighFive\MainBundle\Event\UserEvent;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Listener responsible to add messages on the wall.
 */
class MessagesListener implements EventSubscriberInterface
{
    private $registry;
    private $translator;

    public function __construct(ManagerRegistry $registry, TranslatorInterface $translator)
    {
        $this->registry = $registry;
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            HighFiveEvents::REGISTRATION_REGISTER => 'onRegistration',
            HighFiveEvents::REGISTRATION_INVITED => 'onRegistration',
        );
    }

    public function onRegistration(UserEvent $event)
    {
        $user = $event->getUser();

        $message = new Message();
        $message->setOrganization($user->getOrganization())
            ->setCreatedAt(new \DateTime())
            ->setRecipient($user)
            ->setMessage($this->translator->trans('message.info.registration', array('%first_name%' => $user->getFirstName(), '%last_name%' => $user->getLastName())));

        $this->registry->getManager()->persist($message);
    }
}
