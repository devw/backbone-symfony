<?php

namespace HighFive\MainBundle\EventListener;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Event\RecognitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listener responsible to update the total of points of a user.
 */
class UserPointsListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(HighFiveEvents::RECOGNITION_CREATE => 'onRecognitionCreate');
    }

    public function onRecognitionCreate(RecognitionEvent $event)
    {
        $recognition = $event->getRecognition();
        $user = $recognition->getRecipient();

        $user->addPoints($recognition->getPoints());

        $recognition->getSender()->addGivenPoints($recognition->getPoints());
    }
}
