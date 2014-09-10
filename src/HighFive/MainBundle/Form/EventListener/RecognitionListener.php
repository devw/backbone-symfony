<?php

namespace HighFive\MainBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Listener setting the recognition settings from the message.
 */
class RecognitionListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_BIND => array('postBind', 10));
    }

    public function postBind(FormEvent $event)
    {
        $form = $event->getForm();

        /** @var $message \HighFive\MainBundle\Entity\Message */
        $message = $form->getData();
        $recognition = $message->getRecognition();

        if (null === $recognition) {
            return;
        }

        $recognition->setSender($message->getSender())
            ->setCreatedAt($message->getCreatedAt());

        if (null !== $message->getRecipient()) {
            $recognition->setRecipient($message->getRecipient());
        }
    }
}
