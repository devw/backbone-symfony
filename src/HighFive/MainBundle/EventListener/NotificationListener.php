<?php

namespace HighFive\MainBundle\EventListener;

use Doctrine\Common\Persistence\ManagerRegistry;
use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Entity\Message;
use HighFive\MainBundle\Entity\User;
use HighFive\MainBundle\Event\MessageEvent;
use HighFive\MainBundle\Event\RecognitionEvent;
use HighFive\MainBundle\Notification\NotificationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listener responsible to add notifications
 */
class NotificationListener implements EventSubscriberInterface
{
    private $notificationManager;
    private $registry;

    public function __construct(NotificationManager $notificationManager, ManagerRegistry $registry)
    {
        $this->notificationManager = $notificationManager;
        $this->registry = $registry;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            HighFiveEvents::MESSAGE_REPLY => 'onMessageReply',
            HighFiveEvents::RECOGNITION_CREATE => 'onRecognitionCreate',
        );
    }

    public function onMessageReply(MessageEvent $event)
    {
        $message = $event->getMessage();
        $parent = $message->getParent();

        if (null === $parent) {
            return;
        }

        $recipient = $parent->getRecipient();
        $sender = $parent->getSender();

        if ($message->getSender() !== $recipient) {
            $this->sendReplyNotification($message, $recipient);
        }

        if ($message->getSender() !== $sender && $sender !== $recipient && null !== $sender) {
            $this->sendReplyNotification($message, $sender);
        }

        /** @var $userRepository \HighFive\MainBundle\Doctrine\Repository\UserRepository */
        $userRepository = $this->registry->getRepository('MainBundle:User');

        $users = $userRepository->getRepliers($parent);

        foreach ($users as $user) {
            // Skip the users who already got notified
            if ($sender === $user || $recipient === $user || $message->getSender() === $user) {
                continue;
            }

            $this->sendReplyNotification($message, $user);
        }
    }

    public function onRecognitionCreate(RecognitionEvent $event)
    {
        $recognition = $event->getRecognition();

        $this->notificationManager->send(NotificationManager::TYPE_RECEIVE_POINTS, $recognition->getRecipient(), $recognition, $recognition->getSender());
    }

    private function sendReplyNotification(Message $message, User $recipient)
    {
        $this->notificationManager->send(NotificationManager::TYPE_REPLY, $recipient, $message, $message->getSender());
    }
}
